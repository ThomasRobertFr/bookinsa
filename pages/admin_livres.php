<?php

// verification que l'on est admin
if (!$admin)
	$T['fatal_error'] = 'Accès interdit.';
else
{

$T['jquery'] = true;
$T['js_file'] = 'admin_livres';


///////////////////////////////////////////
// Update timestamp

if (!empty($_GET['timestamp']))
{
	$timestamp = (int) $_GET['timestamp'];
	$php = '<?php $timestamp = '.$timestamp.';';
		file_put_contents('cache/timestamp.php', $php);
}

//////////////////////////////////
// Suppression

elseif (!empty($_GET['del']))
{
	$id = (int) $_GET['del'];

	MySQL::query('DELETE FROM livres WHERE id = :1', $id);
	if(MySQL::affectedRows())
		$T['result'] = 'success';
	else
		$T['result'] = 'error';
}

//////////////////////////////////
// Recup infos d'un livre
elseif (!empty($_GET['get_livre']))
{
	$id = (int) $_GET['get_livre'];
	
	$T['get_livre'] = MySQL::getRow('SELECT id, isbn, googleid, titre, auteur, genre, pubdate, resume, id_demande, timestamp, id_owner FROM livres WHERE id = :1', $id);
	
	if (empty($T['get_livre']))
		$T['get_livre']['error'] = 'true';
	
	$js = true;
}

//////////////////////////////////
// MAJ infos
elseif (!empty($_POST['id']))
{
	$id = (int) $_POST['id'];
	
	$livre = MySQL::getRow('SELECT id FROM livres WHERE id = :1', $id);
	
	// si le livre existe
	if (!empty($livre))
	{
		$fields = array('isbn', 'googleid', 'titre', 'auteur', 'genre', 'pubdate', 'resume', 'id_demande', 'timestamp', 'id_owner');
		$maj_data = array();
		
		// maj donnée
		foreach ($fields as $field)
		{
			// si on doit maj ce champ
			if(isset($_POST[$field.'_maj']) && isset($_POST[$field]))
				$maj_data[$field] = $_POST[$field];
		}
		
		if (!empty($maj_data))
			MySQL::updateRow('livres', array('id' => $id), $maj_data);
		
		// maj converture si le livre existe bien
		if(isset($_POST['thumb_url_maj']) && isset($_POST['thumb_url']))
		{
			if (empty($_POST['thumb_url']))
			{
				unlink('images/'.$id.'l.jpg');
				unlink('images/'.$id.'m.jpg');
				unlink('images/'.$id.'s.jpg');
			}
			elseif (!save_thumb($_POST['thumb_url'], $id, 0, 0))
				$T['maj']['error'] = 'Echec de modification de la couverture';
		}
	}
	else
	{
		$T['maj']['error'] = 'Le livre n\'a pas été trouvé';
	}	
	
	// datas pour l'affichage
	$T['maj']['id'] = $id;
	$T['maj']['titre'] = $_POST['titre'];
	$T['maj']['auteur'] = $_POST['auteur'];
	$T['maj']['id_owner'] = $_POST['id_owner'];	
	
	$js = true;
}

/////////////////////////////////////
// Liste des livres
else
{
	include('cache/timestamp.php');
	$T['timestamp'] = $timestamp;
	$T['livres'] = MySQL::query('SELECT id, titre, auteur, prenom, nom, timestamp FROM livres JOIN users ON livres.id_owner = users.user_id ORDER BY timestamp DESC', $user);
}

} // end if($admin)



