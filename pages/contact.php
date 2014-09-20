<?php

$T['nom'] = '';
$T['email'] = '';
$T['sujet'] = '';
$T['message'] = '';
$T['form'] = false;

if (isset($_POST['message']))
{
	$T['error'] = array();
	
	////////////////////////////////////
	// verifications et variables
	////////////////////////////////////
	
	if (!$user)
	{
		// mail
		$mail = (isset($_POST['email'])) ? trim($_POST['email']) : '';
		if (empty($mail) || !preg_match("#^[A-Za-z0-9._-]+@[A-Za-z0-9._-]{2,}\.[A-Za-z-]{2,4}$#", $mail))
			$T['error'][] = 'Entrez une adresse mail valide.';
		
		// nom
		$nom = (isset($_POST['nom'])) ? substr(trim($_POST['nom']), 0, 40) : '';
		if (empty($nom))
			$T['error'][] = 'Entrez votre nom.';
	}
	else
	{
		$mail = $user_profile['email'];
		$nom = $user_profile['name'].' ('.$user.')';
	}
	
	// sujet
	$sujet = (isset($_POST['sujet'])) ? substr(trim($_POST['sujet']), 0, 300) : '';
	if (empty($sujet))
		$T['error'][] = 'Entrez un sujet a votre mail.';
	
	// message
	$message = (isset($_POST['message'])) ? trim($_POST['message']) : '';
	if (empty($message))
		$T['error'][] = 'Entrez un message.';
	
	// dest
	$dest = 'trobert94@gmail.com';
	
	// captcha
	if(strtolower($_POST['captcha']) != strtolower($_SESSION['captcha']))
		$T['error'][] = 'Le code entré est incorrect.';
	
	////////////////////////////////////
	// envoi
	////////////////////////////////////
	
	if(empty($T['error']))
	{
		// header
		$header = "From: $mail\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/plain\r\n";
		
		$ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		
		$message2 = 'De : '.$nom.' ('.$mail.') ['.$ip.']
Envoyé à : '.date('j/m/Y, g:i').'

'.$message;
		
		if (mail($dest, $sujet, $message2, $header))
			$T['success'] = 'Votre message a bien été envoyé. Nous y répondrons dans les plus brefs délais.';
		else
		{
			$T['error'][] = 'Erreur pendant l\'envoi du mail. Vous pouvez réessayer.';
			$T['form'] = true;
		}
	}
	else
		$T['form'] = true;
		
	if ($T['form'])
	{
		$T['nom'] = $nom;
		$T['email'] = $mail;
		$T['sujet'] = $sujet;
		$T['message'] = $message;
	}
}
else
	$T['form'] = true;
