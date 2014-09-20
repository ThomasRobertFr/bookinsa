<?php
/**
 * Copyright (C) 2010 Thomas Robert (http://thomas-robert.fr).
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// inclusion des fichiers utiles
require 'includes/constantes.php';
require 'includes/functions.php';
require 'includes/sql.class.php';
require 'includes/fbapi/facebook.php';
require 'includes/gbooks.php';
require 'includes/getcover.php';

// initialisation user & uer_profile / enregistrement de l'utilisateur automatique si il vient de se connecter
domain_redirect();
magic_quotes_init();

$facebook = fb_init();
$user = $facebook->getUser();
$user_profile = fb_api('/me', $user, $facebook);
if($user) register_on_login($user_profile);

$admin = init_admin($user);
$js = isset($_GET['js']);

// inclusion de la "logique" de la page
$T = array();	// array contenant tous les élements a mettre dans le template
$include = get_include($T);
if (!$js) include('pages/haut.php');
include('pages/'.$include.'.php');

// inclusion du rendu de la page
if (!$js) include('template/haut.php');
if (empty($T['fatal_error']))
include('template/'.$include.'.php');
if (!$js) include('template/bas.php');
