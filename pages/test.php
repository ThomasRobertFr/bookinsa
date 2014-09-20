<?php 


$title = htmlentities(rawurlencode('BookINSA - '.preg_replace('#<.+>#iU', '', $T['titre'])));
$url = htmlentities(rawurlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]));