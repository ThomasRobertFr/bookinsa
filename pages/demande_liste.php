<?php

$T['js_file'] = 'demande_liste';
$T['jquery'] = true;

// si l'user n'est pas connecté
if (!$user)
	$T['fatal_error'] = 'Vous devez-être connecté pour accéder à cette page. <a href="'.$facebook->getLoginUrl(array('scope' => 'email')).'">Connectez-vous</a>.';
else
{

// recuperation des demandes recues
$T['d_recues'] = MySQL::query('
SELECT demandes.id_demande, demandes.from, demandes.id_livre, demandes.etat, users.prenom, users.nom, livres.id, livres.titre, livres.auteur, COUNT(lu) as messages FROM demandes
JOIN users ON demandes.from = users.user_id
JOIN livres ON demandes.id_livre = livres.id
LEFT JOIN messages ON demandes.id_demande = messages.id_demande
AND messages.lu = 0
AND messages.message_from <> :1
WHERE demandes.to = :1
GROUP BY demandes.id_demande
ORDER BY messages DESC, etat ASC', $user);

// recuperation des demandes envoyées
$T['d_envoyees'] = MySQL::query('
SELECT demandes.id_demande, demandes.to, demandes.id_livre, demandes.etat, users.prenom, users.nom, livres.id, livres.titre, livres.auteur, COUNT(lu) as messages FROM demandes
JOIN users ON demandes.to = users.user_id
JOIN livres ON demandes.id_livre = livres.id
LEFT JOIN messages ON demandes.id_demande = messages.id_demande
AND messages.lu = 0
AND messages.message_from <> :1
WHERE demandes.from = :1
GROUP BY demandes.id_demande
ORDER BY messages DESC, etat ASC', $user);

}