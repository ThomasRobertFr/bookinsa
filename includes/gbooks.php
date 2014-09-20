<?php

// récupérer un livre sur Google Books
function getBook($isbn)
{
	$isbn = preg_replace('/\D/', '', $isbn);
	
	// si l'ISBN est non nul
	if ($isbn)
	{
	
	//////////////////////////////
	// GET ID
	
	// curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);

	// decode & traitement
	$json = json_decode($response, true);
	
	if ($json['totalItems'] == 0)
		return false;
	
	$id = $json['items'][0]['id'];
	
	//////////////////////////////
	// GET infos
	
	// curl
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/books/v1/volumes/'.$id);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);

	// decode & traitement
	$json = json_decode($response, true);
	
	curl_close($ch);
	
	$output = array();
	
	// isbn
	$output['isbn'] = $json['volumeInfo']['industryIdentifiers'][1]['identifier'];
	
	// googleid
	$output['googleid'] = $id;
	
	// titre
	$output['titre'] = $json['volumeInfo']['title'];
	if (isset($json['volumeInfo']['subtitle']))
	$output['titre'] .= ' - '.$json['volumeInfo']['subtitle'];
	
	// auteur
	foreach($json['volumeInfo']['authors'] as $temp)
	{
		if (isset($output['auteur'])) $output['auteur'] .= ', ';
		else $output['auteur'] = '';
		$output['auteur'] .= $temp;
	}
	
	// pubdate
	if (isset($json['volumeInfo']['publishedDate'])) {
	$temp = explode('-', $json['volumeInfo']['publishedDate']);
	if (isset($temp[2])) 		$output['pubdate'] = $temp[2].$temp[1].$temp[0];
	elseif (isset($temp[1])) 	$output['pubdate'] = $temp[1].$temp[0];
	else						$output['pubdate'] = $temp[0];
	} else $output['pubdate'] = 0;
	
	// resumé
	$output['resume'] = (isset($json['volumeInfo']['description'])) ? $json['volumeInfo']['description'] : '';
	
	// image
	$output['thumbs']['g'] = (isset($json['volumeInfo']['imageLinks']));
	
	$sizeimg = getimagesize('http://covers.openlibrary.org/b/isbn/'.$isbn.'-S.jpg');
	$output['thumbs']['ol'] = ($sizeimg['0'] != 1);
	
	return $output;
	}
	else
		return false;
}

