<?php

// verification que l'on est admin
if (!$admin)
	$T['fatal_error'] = 'Accès interdit.';
else
{

/////////////////////////////////////
// gestion du bannissement
if (!empty($_GET['ban']))
{
	// on verifie que l'utilisateur existe
	$id = $_GET['ban'];
	$T['user'] = MySQL::getRow('SELECT COUNT(user_id) as count FROM users WHERE user_id = :1', $id);
	if ($T['user']['count'])
	{
		// si il existe on utilise l'API fb poru le ban
		fb_api(array('method' => 'admin.banUsers', 'uids' => '['.$id.']', 'access_token' => FB_APP_ID.'|'.FB_SECRET), $user, $facebook);
		$T['success'] = 'L\'utilisateur <strong>'.$id.'</strong> a été banni.';
	}
	else
		$T['error'] = 'L\'utilisateur n\'a pas pu être banni.';
}

/////////////////////////////////////
// Gestion du débannissement
elseif (!empty($_GET['unban']))
{
	// on débanni avec l'API fb
	$id = $_GET['unban'];
	fb_api(array('method' => 'admin.unbanUsers', 'uids' => '['.$id.']', 'access_token' => FB_APP_ID.'|'.FB_SECRET), $user, $facebook);
	$T['success'] = 'L\'utilisateur <strong>'.$id.'</strong> a été débanni.';
}

/////////////////////////////////////
// Gestion de la mise admin
elseif (!empty($_GET['admin']))
{
	// on verifie que la personne existe
	$id = $_GET['admin'];
	$T['user'] = MySQL::getRow('SELECT COUNT(user_id) as count FROM users WHERE user_id = :1', $id);
	if ($T['user']['count'])
	{
		// si elle existe, on l'ajoute a la liste des admins et on recrée le fichier de cache contenant cette liste
		$ADMINS[$id] = true;
		$admins = serialize($ADMINS);
		$php = '<?php $admins = \''.$admins.'\';';
		file_put_contents('cache/admins.php', $php);
		
		$T['success'] = 'L\'utilisateur <strong>'.$id.'</strong> est désormais administrateur.';
	}
	else
		$T['error'] = 'L\'utilisateur n\'a pas été trouvé dans la base.';
}

/////////////////////////////////////
// Gestion de la suppression d'admin
elseif (!empty($_GET['unadmin']))
{
	$id = $_GET['unadmin'];
	// si la personne était admin
	if (isset($ADMINS[$id]))
	{
		// on supprimme de la liste et on refait le cache
		unset($ADMINS[$id]);
		$admins = serialize($ADMINS);
		$php = '<?php $admins = \''.$admins.'\';';
		file_put_contents('cache/admins.php', $php);
		
		$T['success'] = 'L\'utilisateur <strong>'.$id.'</strong> n\'est plus administrateur.';
	}
	else
		$T['error'] = 'L\'utilisateur n\'est pas admin.';
}


/////////////////////////////////////
// Récupération de la liste des users
$T['users'] = MySQL::query('SELECT * FROM users');
$T['banned'] = fb_api(array('method' => 'admin.getBannedUsers', 'access_token' => FB_APP_ID.'|'.FB_SECRET), $user, $facebook);

// on verifie pour chaque user si il est ban ou admin
foreach($T['users'] as $key => $auser)
{
	$T['users'][$key]['banned'] = in_array($auser['user_id'], $T['banned']);
	$T['users'][$key]['admin'] = isset($ADMINS[$auser['user_id']]);
}

}