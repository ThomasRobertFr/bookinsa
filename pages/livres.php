<?php

// liste des tris possibles
$SORTS = array(
	'titre' => 'Titre',
	'auteur' => 'Auteur',
	'timestamp' => 'Date d\'ajout'
);

// listes des recherches possibles
$SEARCH_FIELDS = array(
	'titre' => 'Titre',
	'auteur' => 'Auteur',
	'resume' => 'Resumé'
);

////////////////////////////////////
// conditions de la requete SQL

// genre
$genre = (int) (!empty($_GET['genre']) && !empty($GENRES[$_GET['genre']])) ? $_GET['genre'] : 0;
$where = ($genre) ? 'WHERE genre = '.$genre : '';
$T['genre'] = $genre;

// recherche
$qs = array();
$T['search'] = '';
$T['in'] = 'titre';
if(!empty($_GET['search']))
{
	$search = htmlspecialchars(trim($_GET['search']));

	$q = str_replace('\'', '', $search);
	$q = str_replace('_', '\_', $q);
	$q = str_replace('%', '\%', $q);
	$qs = explode(' ', $q);

	// init du where si nécéssaire
	$field = (isset($_GET['in']) && isset($SEARCH_FIELDS[$_GET['in']])) ? $_GET['in'] : 'titre';
	$where .= (empty($where)) ? 'WHERE ' : ' AND ';
	
	// ajout des q
	$first_q = true;
	foreach ($qs as $el)
	{
		$where .= ($first_q) ? $field.' LIKE \'%'.$el.'%\'' : ' AND '.$field.' LIKE \'%'.$el.'%\'';
		$first_q = false;
	}
	
	$T['in'] = $field;
	$T['search'] = $search;
}

// tri
if (!empty($_GET['sort']) && isset($SORTS[$_GET['sort']]))
{
	$T['sort'] = $_GET['sort'];
	if ($_GET['sort'] == 'timestamp')
		$sort = ' ORDER BY timestamp DESC';
	else
		$sort = ' ORDER BY '.$_GET['sort'].' ASC';
}
else
{
	$sort = ' ORDER BY timestamp DESC';
	$T['sort'] = 'timestamp';
}

///////////////////////
// infos sur les pages

// nbr de livres par page
$nbr_par_page = 15;

// nombre de livres
$nbr_livres = MySQL::getRow('SELECT COUNT(id) AS nbr FROM livres '.$where);
$nbr_livres = $nbr_livres['nbr'];

// page en cours
$page_num = 0;
if (isset($_GET['page']))
	$page_num = (int) $_GET['page'];
if ($page_num == 0)
	$page_num  = 1;
	
// numero du 1er livre
$num_debut = ($page_num - 1) * $nbr_par_page;

//////////////////////
// affichage

// variables utiles
$T['page'] = $page_num;
$T['nbr_pages'] = ceil($nbr_livres / $nbr_par_page);

// recup des livres
$T['livres'] = MySQL::query('SELECT id, titre, auteur, id_demande FROM livres '.$where.$sort.' LIMIT '.$num_debut.','.$nbr_par_page);

// recreation du debut de l'url
$T['url_debut'] = 'livres';
if ($genre)
	$T['url_debut'] .= '-'.$genre.'-'.txt2url($GENRES[$genre]);
$T['url_debut'] .= '-page';

// recreation de la fin de l'url

$T['url_fin'] = '.html?sort='.$T['sort'];
if (!empty($T['search']))
	$T['url_fin'] .= '&amp;search='.$T['search'].'&amp;'.$T['in'];

