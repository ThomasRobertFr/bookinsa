<?php

// on demande le chargement de l'API JS Facebook (pour les commentaires)
$T['fb_js'] = true;
$T['jquery'] = true;
$T['js_file'] = 'livre';



//////////////////////////////////
// Vote
//////////////////////////////////

if (!empty($_GET['id']) && !empty($_GET['vote']))
{
	// si l'user est connecté
	if ($user)
	{
		$id = (int) $_GET['id'];
		$vote = (int) $_GET['vote'];
		
		// recuperation du livre (pour verif d'existence) et du vote éventuel de l'user
		$id = (int) $_GET['id'];
		$sql = MySQL::getRow('SELECT livres.id, livres.note, livres.note_votes, notes.note_choix FROM livres LEFT JOIN notes ON livres.id = notes.note_livre_id AND notes.note_user = :1 WHERE livres.id = :2', $user, $id);

		$T['vote_size'] = 0;
		// si le livre existe
		if (!empty($sql))
		{
			$T['vote_size'] = $sql['note'] * 16;
			// si le vote est valide
			if ($vote >= 1 && $vote <= 5)
			{
				// si l'user n'a pas voté
				if (empty($sql['note_choix']))
				{
					$note = ($sql['note'] * $sql['note_votes'] + $vote) / ($sql['note_votes'] + 1);
					$note_votes = $sql['note_votes'] + 1;
					MySQL::updateRow('livres', array('id' => $id), array('note' => $note, 'note_votes' => $note_votes));
					MySQL::insertRow('notes', array('note_livre_id' => $id, 'note_user' => $user, 'note_choix' => $vote));
					
					$T['vote'] = '<span class="green">Votre vote a bien été pris en compte. Merci.</span>';
					$T['success'] = 'Votre vote a bien été pris en compte. Merci.';
					$T['vote_size'] = $note * 16;
				}
				// si l'user a déjà voté (et vote différent)
				elseif ($sql['note_choix'] != $vote)
				{
					$note = $sql['note'] + ($vote / $sql['note_votes']) - ($sql['note_choix'] / $sql['note_votes']);
					MySQL::updateRow('livres', array('id' => $id), array('note' => $note));
					MySQL::updateRow('notes', array('note_livre_id' => $id, 'note_user' => $user), array('note_choix' => $vote));
					
					$T['vote'] = '<span class="green">Votre vote a bien été mis à jour. Merci.</span>';
					$T['success'] = 'Votre vote a bien été mis à jour. Merci.';
					$T['vote_size'] = $note * 16;
				}
				else
				{
					$T['vote'] = 'Votre vote n\'a pas changé par rapport à la dernière fois.';
					$T['success'] = 'Votre vote n\'a pas changé par rapport à la dernière fois.';
				}
			}
			// vote invalide
			else
			{
				$T['vote'] = '<span class="red">Votre vote n\'est pas valide. Réessayez.</span>';
				$T['error'] = 'Votre vote n\'est pas valide. Réessayez.';
			}
		}
		// livre non trouvé
		else
		{
			$T['vote'] = '<span class="red">Le livre est introuvable. Il a probablement été supprimé.</span>';
			$T['fatal_error'] = 'Le livre est introuvable. Il a probablement été supprimé.';
		}
	}
	// non connecté
	else
	{
		$T['vote'] = '<span class="red">Vous devez-être connecté pour voter. <a href="'.$facebook->getLoginUrl(array('scope' => 'email')).'">Connectez-vous</a>.</span>';
		$T['error'] = 'Vous devez-être connecté pour voter. <a href="'.$facebook->getLoginUrl(array('scope' => 'email')).'">Connectez-vous</a>.';
	}
}


//////////////////////////////////
// Affichage
//////////////////////////////////

if (!isset($_GET['js']))
{

// recuperation du livre
$id = (int) $_GET['id'];
$T['livre'] = MySQL::getRow('SELECT id, isbn, titre, auteur, genre, pubdate, resume, id_demande, id_owner, prenom, nom, note, note_votes FROM livres INNER JOIN users
ON livres.id_owner = users.user_id WHERE id = :1', $id);


//////////////////////////////////
// Livre introuvable
if (!$T['livre'])
	$T['fatal_error'] = 'Le livre est introuvable. Il a probablement été supprimé.';
	
//////////////////////////////////
// Suppression
elseif (isset($_GET['del']) && $admin)
{
	MySQL::query('DELETE FROM livres WHERE id = :1', $id);
	$demande = MySQL::getRow('SELECT id_demande FROM demandes WHERE id_livre = :1', $id);
	MySQL::query('DELETE FROM demandes WHERE id_livre = :1', $id);
	MySQL::query('DELETE FROM messages WHERE id_demande = :1', $demande['id_demande']);
	
	$T['fatal_error'] = 'Livre supprimé.';
}

//////////////////////////////////
// Affichage
else
{
	// mise en forme pour l'affichage
	$T['livre']['pubdate_head'] = ($T['livre']['pubdate'] > 1000) ? ' ('.get_pubdate($T['livre']['pubdate']).')' : '';
	$T['livre']['pubdate'] = ($T['livre']['pubdate'] > 1000) ? get_pubdate($T['livre']['pubdate']) : 'Non renseignée';
	$T['livre']['isbn'] = ($T['livre']['isbn'] != 0) ? $T['livre']['isbn'] : 'Non renseigné';
	$T['livre']['genre_txt'] = $GENRES[$T['livre']['genre']];
	
	$T['titre'] = '<em>'.$T['livre']['titre'].'</em> par <em>'.$T['livre']['auteur'].'</em>';
	
	$T['fb_comments_url'] = 'http://'.DOMAIN.'/livre-'.$id.'-'.txt2url($T['livre']['titre']).'.html';
	
	$T['s_title'] = htmlentities(rawurlencode('BookINSA - '.preg_replace('#<.+>#iU', '', $T['titre'])));
	$T['s_url'] = htmlentities(rawurlencode($T['fb_comments_url']));
}

}