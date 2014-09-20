<?php

if (!$user)
	$T['fatal_error'] = 'Vous devez-être connecté pour accéder à cette page. <a href="'.$facebook->getLoginUrl().'">Connectez-vous</a>.';
else
{

//////////////////////////////////
// Suppression

if (isset($_GET['del']))
{
	$id = (int) $_GET['del'];
	//////////////////////////////
	// Suppression réelle
	if (isset($_GET['confirm']))
	{
		MySQL::query('DELETE FROM livres WHERE id = :1 AND id_owner = :2', $id, $user);
		if(MySQL::affectedRows())
		{
			$T['success'] = 'Livre supprimé avec succès.';
		}
		else
			$T['error'] = 'Ce livre ne vous appartient pas ou n\'existe pas.';
	}
	//////////////////////////////
	// Demande de confirmation
	else
	{
		$T['del_confirm'] = true;
		$T['livre'] = MySQL::getRow('SELECT titre FROM livres WHERE id = :1 AND id_owner = :2', $id, $user);
		
		if (empty($T['livre']))
			$T['fatal_error'] = 'Ce livre ne vous appartient pas ou n\'existe pas.';
		else
			$T['livre']['id'] = $id;
	}
}


//////////////////////////////////
// MAJ couverture
if (!empty($_GET['maj']))
{
	$id = (int) $_GET['maj'];
	//////////////////////////////
	// Maj
	if (!empty($_POST['thumb_maj']))
	{
		$T['book'] = MySQL::getRow('SELECT id, titre, auteur, genre FROM livres WHERE id = :1 AND id_owner = :2', $id, $user);
		
		// si le livre n'est pas a l'user
		if (empty($T['book']))
			$T['error'] = 'Ce livre ne vous appartient pas ou n\'existe pas.';
		// si le livre est a l'user, on maj la couverture
		else
		{
			$T['book']['genre'] = $GENRES[$T['book']['genre']];
			// si l'image existe est est suffisament grande
			$thumb_infos = @getimagesize($_POST['thumb_maj']);
			if ($thumb_infos && $thumb_infos[1] >= 100)
			{
				save_thumb($_POST['thumb_maj'], $id, 0, 0);
			}
			else
			{
				$T['error'] = 'La couverture envoyée est introuvable ou trop petite (100px de hauteur minimum).';
				$T['form3'] = true;
			}
		}
	}
	//////////////////////////////
	// Afficher formulaire
	else
	{
		$T['form3'] = true;
		$T['book'] = MySQL::getRow('SELECT id, titre, auteur, genre FROM livres WHERE id = :1 AND id_owner = :2', $id, $user);
		
		if (empty($T['book']))
			$T['fatal_error'] = 'Ce livre ne vous appartient pas ou n\'existe pas.';
		else
			$T['book']['genre'] = $GENRES[$T['book']['genre']];
	}
}


/////////////////////////////////////
// Ajout d'un livre : formulaire

// demande de formulaire
elseif (isset($_GET['add']))
{	
	// Isbn entré, chercher le livre
	if(isset($_POST['isbn']))
	{
		$genre = (int) $_POST['genre'];
		if ($genre > GENRE_MAX || $genre <= 0)
		{
			$T['error'] = 'Vous devez choisir un genre.';
			$T['form1'] = true;
			$T['isbn'] = $_POST['isbn'];
		}
		else
		{
			$book = getBook($_POST['isbn']);
			
			// si le livre est trouvé
			if ($book)
			{
				$book['genre'] = $genre;
				
				$T['success'] = 'Le livre a été trouvé. Le formulaire a été prérempli, choisissez simplement la couverture.';
				$T['isbn'] = true;
				$T['book'] = $_SESSION['book'] = $book;
				$T['book']['pubdate'] = get_pubdate($T['book']['pubdate']);
				$T['book']['thumbs']['url'] = '';
				$T['form2'] = true;
			}
			else
			{
				$T['book']['titre'] = '';
				$T['book']['auteur'] = '';
				$T['book']['genre'] = $genre;
				$T['book']['pubdate'] = '';
				$T['book']['resume'] = '';
				$T['book']['thumbs']['url'] = '';
				
				$T['error'] = 'Le livre est introuvable, vous devez entrer ses informations manuellement.';
				$T['isbn'] = false;
				$_SESSION['book'] = false;
				$T['form2'] = true;
			}
		}
	}
	
	// Infos sur le livre entrées
	elseif(isset($_POST['form2']))
	{
		// si on avait rien trouvé avec l'ISBN
		if (!$_SESSION['book'])
		{
			// on vérifie les infos
			$T['error'] = array();
			$titre = $_POST['titre'];
			if (empty($titre))
			{
				$T['error'][] = 'Vous devez entrer un titre.';
			}
			
			$auteur = $_POST['auteur'];
			if (empty($auteur))
			{
				$T['error'][] = 'Vous devez entrer un auteur.';
			}
			
			$genre = (int) $_POST['genre'];
			if ($genre > GENRE_MAX || $genre <= 0)
			{
				$T['error'][] = 'Vous devez choisir un genre.';
			}
			
			$pubdate = ($_POST['pubdate'] <= 0 && $_POST['pubdate'] <= date('Y')) ? ((int) $_POST['pubdate']) : 0;
			
			$isbn = 0;
			$googleid = 0;
			
			$resume = $_POST['resume'];
		}
		
		// sinon on avait les infos avec l'ISBN, on les recharges juste
		else
		{
			$titre = $_SESSION['book']['titre'];
			$auteur = $_SESSION['book']['auteur'];
			$genre = $_SESSION['book']['genre'];
			$pubdate = $_SESSION['book']['pubdate'];
			$isbn = $_SESSION['book']['isbn'];
			$googleid = $_SESSION['book']['googleid'];
			$resume = $_SESSION['book']['resume'];
		}
		
		// si une erreur, retour au formulaire
		if (!empty($T['error']))
		{
			$T['book']['titre'] = $_POST['titre'];
			$T['book']['auteur'] = $_POST['auteur'];
			$T['book']['genre'] = $_POST['genre'];
			$T['book']['pubdate'] = $_POST['pubdate'];
			$T['book']['resume'] = $_POST['resume'];
			$T['book']['thumbs']['url'] = '';
			$T['isbn'] = false;
			$T['form2'] = true;
		}
		
		// sinon, on continue (= miniature + save)
		else
		{
			///////////////////////////////
			// données de la miniature
			$thumb = '';
			
			// si on avait un isbn, on vérifie google et open library
			if (isset($_SESSION['book']['thumbs']) && isset($_POST['thumb_type']))
			{
				if		($_POST['thumb_type'] == 'g'  && $_SESSION['book']['thumbs']['g'])	$thumb = 'g';
				elseif	($_POST['thumb_type'] == 'ol' && $_SESSION['book']['thumbs']['ol'])	$thumb = 'ol';
			}
			
			// si on a donné une url comme miniature on vérif si la miniature est valide
			if (!$thumb && !empty($_POST['thumb_url']))
			{
				$thumb_infos = @getimagesize($_POST['thumb_url']);
				if ($thumb_infos && $thumb_infos[1] >= 100)
					$thumb = $_POST['thumb_url'];
			}
			
			// si on a rien défini, on vérifie qu'il n'existait pas une image g ou ol, si elle existait, on la prend.
			if ($thumb == '' && isset($_SESSION['book']['thumbs']))
			{
				if		($_SESSION['book']['thumbs']['g'])	$thumb = 'g';
				elseif	($_SESSION['book']['thumbs']['ol'])	$thumb = 'ol';
			}
			
			///////////////////////////////
			// ajout a la base
			MySQL::insertRow('livres', array(
				'isbn' => $isbn,
				'googleid' => $googleid,
				'titre' => $titre,
				'auteur' => $auteur,
				'pubdate' => $pubdate,
				'genre' => $genre,
				'resume' => $resume,
				'timestamp' => time(),
				'id_owner' => $user));
			
			$id = MySQL::insertId();
			save_thumb($thumb, $id, $isbn, $googleid);
			
			// affichage du livre ajouté
			$T['book_added']['id'] = $id;
			$T['book_added']['pubdate'] = get_pubdate($pubdate);
			$T['book_added']['titre'] = $titre;
			$T['book_added']['resume'] = $resume;
			$T['book_added']['auteur'] = $auteur;
			$T['book_added']['genre'] = $GENRES[$genre];	
		}
	}
	
	// sinon, pas de formulaire envoyé, on affiche le 1er
	else
		$T['form1'] = true;
	
	// si on est sur un formulaire, on change le titre
	if (isset($T['form1']) && $T['form1'] || isset($T['form2']) && $T['form2'])
		$T['titre'] .= ' - Ajouter un livre';
}


/////////////////////////////////////
// Liste de mes livres

if(!isset($T['ret_confirm']) && !isset($T['del_confirm']) && !isset($T['form1']) && !isset($T['form2']))
{
	$T['livres'] = MySQL::query('SELECT id, googleid, titre, id_demande, isbn FROM livres WHERE id_owner = :1 ORDER BY titre ASC', $user);
}



} // end if($user)



