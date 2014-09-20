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
	$T['fatal_error'] = 'Livre introuvable.';
else
{

////////////////////////////////////////////
// recuperation de la demande et test

// id
$id = (int) $_GET['id'];

// on sélectionne le livre
$T['livre'] = MySQL::getRow('SELECT id, titre, auteur, id_owner, prenom, nom, email, id_demande FROM livres INNER JOIN users ON livres.id_owner = users.user_id WHERE id = :1', $id);

// si vide ou que c'est le livre de l'user, le livre n'"existe pas"
if (empty($T['livre']) || $T['livre']['id_owner'] == $user || $T['livre']['id_demande'])
	$T['fatal_error'] = 'Livre introuvable ou déjà réservé/prêté.';
else
{
	////////////////////////////////////////////
	// Traitement du formulaire envoyé
	if (!empty($_POST['message']))
	{
		$message = htmlspecialchars(trim('Bonjour,'."\n\n".'J\'aimerais t\'emprunter ce livre.'."\n\n".$_POST['message']));
		
		MySQL::insertRow('demandes', array('from' => $user, 'to' => $T['livre']['id_owner'], 'id_livre' => $T['livre']['id'], 'etat' => 'attente'));
		$id_demande = MySQL::insertId();
		MySQL::insertRow('messages', array('id_demande' => $id_demande, 'timestamp' => time(), 'message' => $message, 'message_from' => $user, 'message_to' => $T['livre']['id_owner'], 'lu' => 0));
		MySQL::updateRow('livres', array('id' => $T['livre']['id']), array('id_demande' => $id_demande));
		
		// on envoie un mail au destinataire
		if ($T['demande']['email'])
		{
			$header = 'From: contact@bookinsa.fr'."\r\n";
			$header .= 'MIME-Version: 1.0'."\r\n";
			$header .= 'Content-type: text/plain'."\r\n";
			
			$message = 'Bonjour,'."\r\n\r\n".'Vous avez reçu un nouveau message sur BookINSA.'."\r\n\r\n".'Rendez vous sur cette page pour le consulter : http://'.DOMAIN.'/demande-'.$id_demande.'-'.txt2url($T['livre']['titre']).'.html'."\r\n\r\n".'A bientôt sur BookINSA.';
			
			@mail($T['livre']['email'], 'BookINSA - Nouveau message reçu', $message, $header);
		}
		
		// redirection
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ./demande-'.$id_demande.'-'.txt2url($T['livre']['titre']).'.html');
	}
}

} // end livre inexistant