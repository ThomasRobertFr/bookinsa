<?php
session_start();

header ("Content-type: image/png");
$image = imagecreate(260, 90);

$blanc = imagecolorallocate($image, 255, 255, 255);
$noir = imagecolorallocate($image, 0, 0, 0);

function create_bw_color($image, $color_start = 0, $color_end = 150)
{
	$couleur_rgb = mt_rand($color_start,$color_end);
	$couleur = imagecolorallocate($image, $couleur_rgb, $couleur_rgb, $couleur_rgb);
	return $couleur;
}

$polices = array('baveuse3d', 'actionj', 'cheri', 'jfrock', 'justanotherfont', 'kartoons');
$Tpolices = count($polices);

//définition des caractères autorisés.
$carac = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$Tcarac = strlen($carac);

//définition des lignes noires
$nb_lignes = mt_rand(20,30);
$i = 1;
while($i<=$nb_lignes)
{
	$couleur = create_bw_color($image, 75, 200);
	ImageLine($image, mt_rand(0,40), mt_rand(0,90), mt_rand(220, 260), mt_rand(0,90), $couleur);
	$i++;
}

//définition des ellipses
/*
$nb_ellipses = mt_rand(1,6);
$i = 1;
while($i<= $nb_ellipses)
{
	$couleur = create_bw_color($image);
	ImageEllipse($image, mt_rand(0,320), mt_rand(0,100), 25+mt_rand(0,15), 25+mt_rand(0,15), $couleur);
	$i++;
}
*/

//définition des triangles
/*
$nb_triangles = mt_rand(1,6);
$i = 1;
while($i<=$nb_triangles)
{
	$couleur = create_bw_color($image);
	$array = Array(mt_rand(0,300), mt_rand(0,100), mt_rand(0,300), mt_rand(0,100), mt_rand(0,300), mt_rand(0,100));
	ImagePolygon($image, $array, 3, $couleur);
	$i++;
}
*/

$ecart = 35; //écart entre les caractères

$_SESSION['captcha'] = '';

$i = 0;
while($i <= 5)
{
	$couleur = create_bw_color($image, 0, 100);
	$lettre = $carac[mt_rand(0, $Tcarac-1)]; //choix de lettre
	$_SESSION['captcha'] .= $lettre; //stockage
	
	$taille = mt_rand(35,45); //taille
	$angle = mt_rand(-20,20); //angle
	$y = mt_rand(55, 60); //ordonnée
	$police = $polices[mt_rand(0, $Tpolices-1)]; //police
	
	imagettftext($image, $taille, $angle, $ecart*$i+15, $y, $couleur, $police.'.ttf', $lettre);
	$i++;
}

imagepng($image);
?>
