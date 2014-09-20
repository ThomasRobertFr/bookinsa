<?php

function domain_redirect()
{
	if ($_SERVER['SERVER_NAME'] != DOMAIN)
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: http://'.DOMAIN.$_SERVER['REQUEST_URI']);
	}
}

// accès a l'API Facebook
function fb_api($api, &$user, &$facebook)
{
	$return = false;
	if ($user)
	{
		try
		{
			$return = $facebook->api($api);
		}
		catch (FacebookApiException $e)
		{
			// print_r($e); // debug
			$user = null;
		}
	}
	
	return $return;
}

// initialisation de l'API facebook
function fb_init()
{
	return new Facebook(array(
	  'appId'  => FB_APP_ID,
	  'secret' => FB_SECRET,
	));
}


// désactive les magic quotes si elles sont activées sur le serveur.
function magic_quotes_init()
{
	if(get_magic_quotes_gpc())
	{
	   $_GET = array_map('stripslashes', array_map('trim', $_GET));
	   $_POST = array_map('stripslashes', array_map('trim', $_POST));
	   $_COOKIE = array_map('stripslashes', array_map('trim', $_COOKIE));
	}
}


// init de l'admin (vrai si l'user est admin, faux s'il ne l'es pas)
function init_admin($user)
{
	global $ADMINS;
	if (isset($ADMINS[$user]))
		return true;
	else
		return false;
}

// enregistrer l'utilisateur a la connexion si pas déjà enegistré
function register_on_login($user_profile)
{
	// si on vient de se logger
	if(isset($_GET['fblogin']) && !empty($user_profile['id']))
	{
		if (!isset($user_profile['email'])) $user_profile['email'] = '';
		// on recherche l'user dans la base. Si il existe, on le met a jour si il a changé de nom ou de mail, sinon on l'ajoute
		$user = MySQL::getRow('SELECT * FROM users WHERE user_id = :1', $user_profile['id']);
		if ($user)
		{
			if ($user_profile['first_name'] != $user['prenom'] || $user_profile['last_name'] != $user['nom'] || ($user_profile['email'] != $user['email'] && $user_profile['email'] != ''))
				MySQL::updateRow('users', array('user_id' => $user_profile['id']), array('prenom' => $user_profile['first_name'], 'nom' => $user_profile['last_name'], 'email' => $user_profile['email']));
		}
		else
		{
			MySQL::insertRow('users', array('user_id' => $user_profile['id'], 'prenom' => $user_profile['first_name'], 'nom' => $user_profile['last_name'], 'email' => $user_profile['email']));
		}
	}
}

// générer une date de publication "propre" (ex : 12112011 => 12/11/2011)
function get_pubdate($date)
{
	if ($date < 1000)
		return false;
	if(strlen($date) == 8)
		return $date[0].$date[1].'/'.$date[2].$date[3].'/'.$date[4].$date[5].$date[6].$date[7];
	elseif(strlen($date) == 6)
		return $date[0].$date[1].'/'.$date[2].$date[3].$date[4].$date[5];
	else
		return $date;
}

// recuperer le nom du fichier a inclure
function get_include(&$T)
{
	global $PAGES;
	if (isset($_GET['p']) && isset($PAGES[$_GET['p']]))
	{
		$T['titre'] = $PAGES[$_GET['p']];
		return $_GET['p'];
	}
	else
	{
		$T['titre'] = 'Accueil';
		return 'home';
	}
}

// affiche le texte au singulier ou au pluriel
function plur($nbr, $sing, $plur, $show_zero = true)
{
	if (!$show_zero && $nbr == 0)
		return '';
	elseif ($nbr <= 1)
		return $nbr.' '.$sing;
	else
		return $nbr.' '.$plur;
}


// générer un <select name="$name"> a partir d'un array
// si $first_empty on ajoute un <option> vide au début
function gen_select($array, $name, $first_empty = false, $selected = false, $disabled = false)
{
	// on initialise la sortie
	$html = '<select id="i_'.$name.'" name="'.$name.'"'. (($disabled) ? ' disabled="disabled"' : '') .'>';
	
	// si on doit ajouter un vide
	if($first_empty)
		$html .= '<option value=""></option>';
	
	// on rempli
	foreach ($array as $key => $val)
	{
		$select = ($selected == $key) ? 'selected="selected" ' : '';
		// si pas de valeur, c'est un espace, sinon c'est un genre
		if (empty($val))
			$html .= '<option disabled="disabled"></option>';
		else
			$html .= '<option '.$select.'value="'.$key.'">'.$val.'</option>';
	}
	
	// on termine avant re renvoyer
	$html .= '</select>';
	
	return $html;
}

// affiche une date propre a partir d'un timestamp. Si $force_complete, on affiche la date complète, sinon en différé (il y a ...)
function my_date($time, $force_complete = false)
{
	$diff = time() - $time;
	if($diff < 0) return false;
	
	$sec = $diff % 60;
	$min = ($diff-$sec) / 60 % 60;
	$heure = ($diff - $sec - $min * 60) / 3600 % 24;
	
	$minuit = mktime('0','0','0',date('m'),date('d'),date('Y'));
	$hier = mktime('0','0','0',date('m'),date('d')-1,date('Y'));
	
	if ($force_complete)	return 'Le '.date('d/m/Y \à H:i:s', $time);
	
	if($diff < 60)			return 'Il y a '.$diff.'s';
	elseif($diff < 3600)	return 'Il y a '.$min.' min';
	elseif($diff < 7200)	return 'Il y a '.$heure.'h'.$min;
	elseif($time > $minuit)	return 'Aujourd\'hui à '.date('H:i', $time);
	elseif($time > $hier)	return 'Hier à '.date('H:i', $time);
	else					return 'Le '.date('d/m/Y \à H:i:s', $time);
}

// transforme un texte pour etre inclus dans une URLs
function txt2url($str, $petit = true, $separator = '-')
{
	if ($petit)
		$str = mb_strtolower($str);
	
	$str = str_replace(array('&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;'),array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y'),$str);
	
	$str = str_replace(
	array('ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',  'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'œ',  'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ý', 'þ', 'ÿ', 'ŕ'),
	array('b', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'oe', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'b', 'y', 'r'),
	$str);
	
	$str = preg_replace('`[^A-Za-z0-9'.$separator.']+`', $separator, $str);
	$str = preg_replace('`'.$separator.'{2,}`', $separator, $str);

	$str = trim($str, $separator);
	
	return $str;
}


// recupérer une liste de pages
function liste_pages($num_page, $nbr_pages, $url_before_num, $url_after_num = '', $nbr_a_afficher = 4, $show_prev_next = true)
{
	$output = '';
	
	// pas de pages : return
	if ($nbr_pages <= 1) return '<strong>1</strong>';
	
	// debut et fin d'affichage
	$page_start = $num_page - $nbr_a_afficher;
	$page_end   = $num_page + $nbr_a_afficher;
	$before_num = $num_page - 1;
	
	// limitations du début de de la fin
	if ($page_start < 1) $page_start = 1;
	if ($page_end > $nbr_pages) $page_end = $nbr_pages;
	
	// [< Page précédente]
	if ($num_page != 1 && $show_prev_next)
	{
		$output .= '<a href="'.$url_before_num.$before_num.$url_after_num.'">&lt; Page précédente</a> ';
	}
	
	// afficher "[1]" si on ne part de 2
	if ($page_start == 2)
	{
		$before_num = $num_page - 1;
		$output .= '<a href="'.$url_before_num.'1'.$url_after_num.'">1</a> ';
	}
	
	// afficher "[1] [...]" si on ne part pas de 1 ni 2
	elseif ($page_start != 1)
	{
		$before_num = $num_page - 1;
		$output .= '<a href="'.$url_before_num.'1'.$url_after_num.'">1</a> <span class="num_page">...</span> ';
	}
	
	// afficher les pages
	for ($i=$page_start; $i <= $page_end; $i++)
	{
		if ($i != $num_page)
			$output .= '<a href="'.$url_before_num.$i.$url_after_num.'">'.$i.'</a> ';
		else
			$output .= '<strong>'.$i.'</strong> ';
	}
	
	// afficher " [Fin]" si on fini a l'avant dernier
	if ($page_end == $nbr_pages - 1)
	{
		$after_num = $num_page + 1;
		$output .= '<a href="'.$url_before_num.$nbr_pages.$url_after_num.'">'.$nbr_pages.'</a> ';
	}
	
	// afficher "[...] [Fin]" si on ne fini pas a la fin
	elseif ($page_end != $nbr_pages)
	{
		$after_num = $num_page + 1;
		$output .= '<span class="num_page">...</span> <a href="'.$url_before_num.$nbr_pages.$url_after_num.'">'.$nbr_pages.'</a> ';
	}
	
	// [Page suivante >]
	if ($num_page != $nbr_pages && $show_prev_next)
	{
		$output .= '<a href="'.$url_before_num.($num_page + 1).$url_after_num.'">Page suivante &gt;</a> ';
	}
	
	return $output;
}

function save_thumb($thumb, $id, $isbn, $googleid)
{
	if ($thumb == '')
		return false;
	if ($thumb == 'g')
		$url = 'http://books.google.fr/books?id='.$googleid.'&printsec=frontcover&img=1&zoom=1';
	elseif ($thumb == 'ol')
		$url = 'http://covers.openlibrary.org/b/isbn/'.$isbn.'-L.jpg';
	else
		$url = $thumb;
	
	$image = new GetCover();
	if ($image->load($url))
	{
		$image->resize(170);
		$image->save($id.'l.jpg');
		$image->resize(120);
		$image->save($id.'m.jpg');
		$image->resize(35);
		$image->save($id.'s.jpg');
		
		return true;
	}
	return false;
}

function get_thumb($id, $size)
{
	// si pas une taille valide, on met l
	if ($size != 's' && $size != 'm' && $size != 'l') $size = 'l';
	
	// si le fichier existe, on l'affiche, sinon on affiche la mini par defaut
	if(is_file('images/'.$id.$size.'.jpg'))
		return 'images/'.$id.$size.'.jpg';
	else
		return 'images/0'.$size.'.jpg';
}



