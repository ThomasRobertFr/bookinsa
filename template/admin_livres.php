<?php

///////////////////////////////////////////
// Update timestamp

if (!empty($_GET['timestamp']))
{
	echo 'ok';
}

///////////////////////////////////////////
// Suppression

elseif (!empty($_GET['del']))
{
	echo $T['result'];
}


///////////////////////////////////////////
// Recup données

elseif (!empty($T['get_livre'])) {
	echo json_encode($T['get_livre']);
}


///////////////////////////////////////////
// Maj : demande de confirmation

elseif (isset($T['maj'])) { ?>
		<td class="center"><img class="img-s" src="<?php echo get_thumb($T['maj']['id'], 's') ?>?<?php echo time() ?>" /></td>
		<td>
			<a href="#" title="Supprimer le livre"><img src="style/del.png" alt="Suppr." /></a>
			<a href="#" onclick="open_dial(<?php echo $T['maj']['id'] ?>); return false;" title="Mettre à jour la couverture"><img src="style/maj.png" alt="Maj." /></a>
			<a href="<?php echo 'livre-'.$T['maj']['id'].'-'.txt2url($T['maj']['titre']).'.html'; ?>"><?php echo $T['maj']['titre'] ?></a></td>
		<td><?php echo $T['maj']['auteur'] ?></td>
		<td><?php echo $T['maj']['id_owner'] ?></td>
	<?php
	if (isset($T['maj']['error'])) { ?>
		</tr><tr><td colspan="4"><?php echo $T['maj']['error'] ?></td>
	<?php }
}

/////////////////////////////////////
// Liste des livres

else { ?>

<div id="mod_user" title="Modifier le livre">
	<form action="admin-livres.html" method="post"><input type="hidden" id="i_id" name="id" value="" />
		<table>
			<tr>
				<td><label for="i_isbn">ISBN</label></td>
				<td><input type="checkbox" id="i_isbn_maj" name="isbn_maj" /> <input id="i_isbn" type="text" name="isbn" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_googleid">GoogleId</td>
				<td><input type="checkbox" id="i_googleid_maj" name="googleid_maj" /> <input id="i_googleid" type="text" name="googleid" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_titre">Titre</label></td>
				<td><input type="checkbox" id="i_titre_maj" name="titre_maj" /> <input id="i_titre" type="text" name="titre" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_auteur">Auteur</td>
				<td><input type="checkbox" id="i_auteur_maj" name="auteur_maj" /> <input id="i_auteur" type="text" name="auteur" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_genre">Genre</td>
				<td><input type="checkbox" id="i_genre_maj" name="genre_maj" /> <?php echo gen_select($GENRES, 'genre', true) ?></td>
			</tr>
			<tr>
				<td class="has_desc"><label for="i_pubdate">Date pub.<br/><em>Format JJMMYYYY</em></td>
				<td><input type="checkbox" id="i_pubdate_maj" name="pubdate_maj" /> <input id="i_pubdate" type="text" name="pubdate" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_resume">Résumé</td>
				<td><input type="checkbox" id="i_resume_maj" name="resume_maj" /> <textarea id="i_resume" name="resume"></textarea></td>
			</tr>
			<tr>
				<td><label for="i_id_demande">ID demande</td>
				<td><input type="checkbox" id="i_id_demande_maj" name="id_demande_maj" /> <input id="i_id_demande" type="text" name="id_demande" value="" /></td>
			</tr>
			<tr>
				<td class="has_desc"><label for="i_timestamp">Date d'ajout<br/><em>Format UNIX</em></td>
				<td><input type="checkbox" id="i_timestamp_maj" name="timestamp_maj" /> <input id="i_timestamp" type="text" name="timestamp" value="" /></td>
			</tr>
			<tr>
				<td><label for="i_id_owner">ID du possesseur</td>
				<td><input type="checkbox" id="i_id_owner_maj" name="id_owner_maj" /> <input id="i_id_owner" type="text" name="id_owner" value="" /></td>
			</tr>
			<tr>
				<td>Couverture</td>
				<td><input type="checkbox" id="i_thumb_url_maj" name="thumb_url_maj" /> <input id="i_thumb_url" type="text" name="thumb_url" value="" /></td>
			</tr>
		</table>
	</form>
</div>

<table class="table">
	<tr>
		<th></th>
		<th>Titre</th>
		<th>Auteur</th>
		<th>À</th>
	</tr>
	
	<?php foreach ($T['livres'] as $livre) { ?>
	<tr id="row_<?php echo $livre['id'] ?>" class="row<?php if($livre['timestamp'] > $T['timestamp']) echo ' hl2'; ?>">
		<td class="center"><img class="img-s" src="<?php echo get_thumb($livre['id'], 's') ?>" /></td>
		<td>
			<a href="#" onclick="del(<?php echo $livre['id'] ?>); return false;" title="Supprimer le livre"><img src="style/del.png" alt="Suppr." /></a>
			<a href="#" onclick="open_dial(<?php echo $livre['id'] ?>); return false;" title="Mettre à jour la couverture"><img src="style/maj.png" alt="Maj." /></a>
			<?php if($livre['timestamp'] > $T['timestamp']) {?>
			<a href="#" id="val_<?php echo $livre['id'] ?>" onclick="validate(<?php echo $livre['timestamp'] ?>, <?php echo $livre['id'] ?>); return false;" title="Marquer comme vu"><img src="style/ok.png" alt="Ok" /></a><?php } ?>
			<a href="<?php echo 'livre-'.$livre['id'].'-'.txt2url($livre['titre']).'.html'; ?>"><?php echo $livre['titre'] ?></a></td>
		<td><?php echo $livre['auteur'] ?></td>
		<td><?php echo $livre['prenom'] ?> <?php echo $livre['nom'] ?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>