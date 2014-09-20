<?php

// recup des livres
$T['livres'] = MySQL::query('SELECT id, titre, auteur, id_demande FROM livres ORDER BY timestamp DESC LIMIT 0,5');
