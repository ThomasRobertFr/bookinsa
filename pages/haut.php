<?php

////////////////////////////////////////////////////////////////////////
// on récupère le nombre de messages non lu et on affiche aun caractère UTF-8 avec un rond autour du nombre.
if ($user)
{
	// recup du nombre de messages nouveaux
	$demandes = MySQL::getRow('SELECT COUNT(lu) as new_messages FROM messages WHERE message_to = :1 AND lu = 0', $user);
	
	// transformation pour utiliser les nombres cerclés unicode
	if ($demandes['new_messages'] == 0)
		$T['new_messages'] = '';
	elseif($demandes['new_messages'] >= 1 && $demandes['new_messages'] <= 10)
		$T['new_messages'] = ' <span id="new_msg1">&#'. (10101 + $demandes['new_messages']) .';</span>';
	elseif($demandes['new_messages'] >= 10 && $demandes['new_messages'] <= 20)
		$T['new_messages'] = ' <span id="new_msg2">&#'. (9440 + $demandes['new_messages']) .';</span>';
	else
		$T['new_messages'] = ' <span id="new_msg3">('. $demandes['new_messages'] .')</span>';
}

////////////////////////////////////////////////////////////////////////
// ADMIN : on regarde combien de livres n'ont pas été checkés

if ($admin)
{
	// get timestamp
	include('cache/timestamp.php');
	
	// recup du nombre de messages livres
	$livres = MySQL::getRow('SELECT COUNT(id) as num FROM livres WHERE timestamp > :1', $timestamp);
	
	// transformation pour utiliser les nombres cerclés unicode
	if ($livres['num'] == 0)
		$T['new_books'] = '';
	elseif($livres['num'] >= 1 && $livres['num'] <= 10)
		$T['new_books'] = ' <span id="new_msg1">&#'. (10101 + $livres['num']) .';</span>';
	elseif($livres['num'] >= 10 && $livres['num'] <= 20)
		$T['new_books'] = ' <span id="new_msg2">&#'. (9440 + $livres['num']) .';</span>';
	else
		$T['new_books'] = ' <span id="new_msg3">('. $livres['num'] .')</span>';
}