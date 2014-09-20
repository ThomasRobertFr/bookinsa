<?php

$T['js_file'] = 'demande';
$T['jquery'] = true;

////////////////////////////////////////////
// test préliminaires

// si pas connecté, erreur
if (!$user)
	$T['fatal_error'] = 'Vous devez-être connecté pour accéder à cette page. <a href="'.$facebook->getLoginUrl(array('scope' => 'email')).'">Connectez-vous</a>.';
// si pas d'id demandé, erreur
elseif (empty($_GET['id']))
	$T['fatal_error'] = 'Demande introuvable.';
else
{

////////////////////////////////////////////
// recuperation de la demande et test

// id
$id = (int) $_GET['id'];

// on sélectionne la demande
$T['demande'] = MySQL::getRow('
SELECT demandes.id_demande, demandes.id_livre, demandes.from, demandes.to, demandes.etat, users.prenom, users.nom, users.email, livres.id, livres.titre, messages.timestamp FROM demandes
JOIN users ON (demandes.from = users.user_id AND demandes.from <> :1) OR (demandes.to = users.user_id AND demandes.to <> :1)
JOIN livres ON demandes.id_livre = livres.id
LEFT JOIN messages ON messages.id_demande = :2 AND messages.message_from = :1
WHERE demandes.id_demande = :2
ORDER BY timestamp DESC
LIMIT 0,1', $user, $id);


// si vide ou que l'user n'en fait pas parti, la demande n'"existe pas"
if (empty($T['demande']) || ($T['demande']['to'] != $user && $T['demande']['from'] != $user))
	$T['fatal_error'] = 'Demande introuvable.';
else
{

////////////////////////////////////////////
// traitement des données de la demande

// demande recue ou envoyée
$T['recue'] = $recue = ($T['demande']['from'] == $user) ? false : true;
$T['demande']['notme'] = ($recue) ? $T['demande']['from'] : $T['demande']['to'];

// def des 2 users de la discussion
$T['users'][$user] = $user_profile['name'];
$T['users'][$T['demande']['notme']] = $T['demande']['prenom'].' '.$T['demande']['nom'];

// redef du titre
$T['titre'] = 'Demande du livre <em>'.$T['demande']['titre'].'</em> par <em>'.$T['users'][$T['demande']['notme']].'</em>';


////////////////////////////////////////////
// Traitement du formulaire envoyé
if (!empty($_POST))
{
	$message = htmlspecialchars($_POST['message']);
	
	// si l'user accepte la demande (et qu'il a le droit)
	if (isset($_POST['accept']) && $_POST['accept'] && $T['demande']['etat'] == 'attente' && $recue)
	{
		MySQL::updateRow('demandes', array('id_demande' => $id), array('etat' => 'prete'));
		$message = '<em>'.$user_profile['first_name'].' a accepté votre demande de prêt.</em>'."\n\n".$message;
		$T['demande']['etat'] = 'prete';
	}
	
	// si l'user refuse la demande (et qu'il a le droit)
	if (isset($_POST['decline']) && $_POST['decline'] && $T['demande']['etat'] == 'attente' && $recue)
	{
		MySQL::updateRow('demandes', array('id_demande' => $id), array('etat' => 'refuse'));
		MySQL::updateRow('livres', array('id' => $T['demande']['id_livre']), array('id_demande' => '0'));
		$message = '<em>'.$user_profile['first_name'].' a décliné votre demande de prêt.</em>'."\n\n".$message;
		$T['demande']['etat'] = 'refuse';
	}
	
	// si l'user indique le livre rendu (et qu'il a le droit)
	if (isset($_POST['rendu']) && $_POST['rendu'] && $T['demande']['etat'] == 'prete' && $recue)
	{
		MySQL::updateRow('demandes', array('id_demande' => $id), array('etat' => 'rendu'));
		MySQL::updateRow('livres', array('id' => $T['demande']['id_livre']), array('id_demande' => '0'));
		$message = '<em>'.$user_profile['first_name'].' a indiqué que le livre a été rendu.</em>'."\n\n".$message;
		$T['demande']['etat'] = 'rendu';
	}
	
	// on "nettoie" le message
	$message = trim($message);
	
	// message vide
	if (empty($message))
		$T['error'] = 'Le message envoyé est vide.';
	// verif anti-flood : 1 message / min max
	if ($T['demande']['timestamp'] > (time() - 60))
		$T['error'] = 'Vous ne pouvez pas envoyer deux messages à moins d\'une minute d\'intervalle. Veuillez patienter s\'il vous plait...';
	// envoi
	else
	{
		MySQL::insertRow('messages', array('id_demande' => $id, 'timestamp' => time(), 'message' => $message, 'message_from' => $user, 'message_to' => $T['demande']['notme'], 'lu' => 0));
		
		// on envoie un mail au destinataire
		if ($T['demande']['email'])
		{
			$header = 'From: contact@bookinsa.fr'."\r\n";
			$header .= 'MIME-Version: 1.0'."\r\n";
			$header .= 'Content-type: text/plain'."\r\n";
			
			$message = 'Bonjour,'."\r\n\r\n".'Vous avez reçu un nouveau message sur BookINSA.'."\r\n\r\n".'Rendez vous sur cette page pour le consulter : http://'.DOMAIN.'/demande-'.$T['demande']['id_demande'].'-'.txt2url($T['demande']['titre']).'.html'."\r\n\r\n".'A bientôt sur BookINSA.';
			
			@mail($T['demande']['email'], 'BookINSA - Nouveau message reçu', $message, $header);
		}
	}
}


////////////////////////////////////////////
// recuperation des messages

// on selectionne les données
$T['messages'] = MySQL::query('
SELECT messages.timestamp, messages.message, messages.message_from, messages.lu, users.prenom, users.nom FROM messages
JOIN users ON messages.message_from = users.user_id
WHERE messages.id_demande = :1
ORDER BY messages.timestamp ASC', $id);

// on indique les messages comme lus
MySQL::updateRow('messages', array('id_demande' => $id, 'message_from' => $T['demande']['notme']), array('lu' => 1));


} // end demande inexistante
} // end non connecté