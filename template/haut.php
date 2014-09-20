<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title><?php echo 'BookINSA - '.preg_replace('#<.+>#iU', '', $T['titre']) ?></title>
	
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="Content-Language" content="fr" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="index,follow" />
	<?php if (isset($T['fb_js'])) { ?><meta property="fb:app_id" content="<?php echo FB_APP_ID ?>"/><?php } ?>
	
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<link rel="stylesheet" href="style/style.css" type="text/css" />
	
	<?php if (isset($T['jquery']) || $admin) { ?>
	<link type="text/css" href="style/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	<?php } ?>
	<?php if ($admin) { ?>
	<script type="text/javascript" src="js/admin.js"></script>
	<?php } ?>
	<?php if (isset($T['js_file'])) { ?>
	<script type="text/javascript" src="js/<?php echo $T['js_file'] ?>.js"></script>
	<?php } ?>
	
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-12985376-2']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>

<?php if (isset($T['fb_js'])) { ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=210982002300875";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php } ?>

<body>
	<div id="leftbar">
		<div id="title"><a href="accueil.html"><img src="style/logo.png" alt="BookINSA" /></a></div>
		<div id="menu">
			<p><a href="accueil.html">Accueil</a></p>
			<p><a href="livres.html">Voir les livres</a></p>
			<?php if ($user) { ?>
			<p class="space"><a href="mes-livres.html">Mes livres</a></p>
			<p><a href="mes-partages.html">Mes partages<?php echo $T['new_messages'] ?></a></p>
			<?php } else { ?>
			<p><a href="<?php echo $facebook->getLoginUrl(array('scope' => 'email')); ?>">Connexion</a></p>
			<?php } ?>
			<p class="space"><a href="faq.html">FAQ / Aide</a></p>
			<p><a href="contact.html">Contact</a></p>
			<?php if ($admin) { ?>
			<p class="space"><a href="admin-livres.html">Gérer les livres<?php echo $T['new_books'] ?></a></p>
			<p><a href="utilisateurs.html">Utilisateurs</a></p>
			<?php } ?>
		</div>
		<?php if ($user) { ?>
		<div id="profil">
			<img src="https://graph.facebook.com/<?php echo $user; ?>/picture?type=normal" height="70" />
			<p class="name"><?php echo $user_profile['first_name']; ?><br/>
			<?php echo $user_profile['last_name']; ?></p>
			<p><a href="<?php echo $facebook->getLogoutUrl(); ?>">Déconnexion</a></p>
		</div>
		<?php } ?>
		<div id="fb-follow">Suivez-nous sur <a href="http://www.facebook.com/BookINSA">Facebook</a></div>
	</div>
	<div id="main">
		<?php echo (!empty($T['titre'])) ? '<h1><span class="text">'.$T['titre'].'</span><span class="end"></span></h1>' : ''; ?>
		<?php if (!empty($T['success'])) { ?><div id="ok_message"><?php echo (is_array($T['success'])) ? implode('<br/>', $T['success']) : $T['success']; ?></div><?php } ?>
		<?php if (!empty($T['error'])) { ?><div id="error_message"><?php echo (is_array($T['error'])) ? implode('<br/>', $T['error']) : $T['error']; ?></div><?php } ?>
		<?php if (!empty($T['fatal_error'])) { ?><div id="error_message"><?php echo (is_array($T['fatal_error'])) ? implode('<br/>', $T['fatal_error']) : $T['fatal_error']; ?></div><?php } ?>