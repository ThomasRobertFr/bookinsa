<?php

// on demande le chargement de l'API JS Facebook (pour les commentaires)
$T['fb_js'] = true;

// chargement de l'utilisateur
$id = (int) $_GET['id'];
$T['user'] = MySQL::getRow('SELECT prenom, nom FROM users WHERE user_id = :1', $id);

//////////////////////////////////
// User introuvable
if (!$T['user'])
	$T['fatal_error'] = 'Utilisateur introuvable.';

//////////////////////////////////
// Affichage
else
{
	// livres
	$T['livres'] = MySQL::query('SELECT id, titre FROM livres WHERE id_owner = :1', $id);
	
	// traitement données
	$T['nom'] = $T['user']['prenom'].' '.$T['user']['nom'];
	$T['user'] = $id;
	$T['titre'] = '<img src="https://graph.facebook.com/'.$T['user'].'/picture?type=square" alt="Photo" /> Profil de <em>'.$T['nom'].'</em>';
	
	$T['fb_comments_url'] = 'http://'.DOMAIN.'/profil-'.$id.'-'.txt2url($T['nom']).'.html';
}