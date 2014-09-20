<?php

// encode UTF-8
mb_internal_encoding ('UTF-8');

// inclusion du cache de la liste des administrateurs
include('cache/admins.php');
$ADMINS = unserialize($admins);

// constantes d'accès a la base de donnée
define('DB_HOST',		'localhost');
define('DB_DATABASE',		'bookinsa');
define('DB_USER',		'root');
define('DB_PASSWORD',		'');

define('DOMAIN',		'localhost');

define('FB_APP_ID',		'<FBAPI>');
define('FB_SECRET',		'<FBSEC>');

// nombre de genres (utilisé pour vérifier si on a bien entré un genre existant)
define('GENRE_MAX', 22);

// liste des genres de livres possibles. (Les vides permettents de mettre un séparateur dans les selecteur HTML)
$GENRES = array(
	1 => 'Roman',
	2 =>'Roman historique',
	3 => 'Roman épistolaire',
	4 => 'Roman d\'amour',
	5 => 'Roman policier',
	6 => 'Fantastique',
	7 => 'Science-fiction',
	8 => 'Horreur',
	9 => 'Biographie',
	10 => 'Conte',
	11 => 'Fable',
	12 => 'Nouvelle',
	13 => 'Témoignage',
	14 => 'Poésie',
	15 => 'Théatre',
	16 => 'Essai',
	17 => 'Enfant',
	'sp1' => '',
	18 => 'BD',
	19 => 'Manga',
	20 => 'Comics',
	'sp2' => '',
	21 => 'Revues',
	'sp3' => '',
	22 => 'Autres'
);

// textes des états d'une demande
$STATES = array(
	'attente' => 'En attente',
	'prete' => 'Prêté',
	'rendu' => 'Rendu',
	'refuse' => 'Refusé'
);

// liste des pages existantes et de leur titres
$PAGES = array(
	'home'			=> 'Accueil',
	'livres'		=> 'Voir les livres',
	'meslivres'		=> 'Mes livres',
	'demande_liste'	=> 'Liste des demandes de prêt',
	'demande_new'	=> 'Nouvelle demande',
	'demande'		=> 'Demande de prêt',
	'livre'			=> 'Voir un livre',
	'users'			=> 'Utilisateurs',
	'admin_livres'	=> 'Administrer les livres',
	'user'			=> 'Profil',
	'contact'		=> 'Contact',
	'faq'			=> 'FAQ / Aide'
);

?>
