<?php

///////////////////////////////////////////
// Suppression : demande de confirmation

if (isset($T['del_confirm'])) { ?>
<p>Vous êtes sur le point de supprimer le livre <strong><?php echo $T['livre']['titre']; ?></strong>. Etes-vous sûr de vouloir continuer ?</p>
<p class="center"><a class="button mr10" href="mes-livres-del-<?php echo $T['livre']['id'] ?>-confirm.html">Confirmer</a> <a class="button" href="mes-livres.html">Annuler</a></p>
<?php }

/////////////////////////////////////
// Ajout d'un livre : formulaire isbn

elseif (isset($T['form1'])) { ?>
<form action="ajouter-un-livre.html" method="post">
	<p>Pour ajouter un livre, entrez son code ISBN : <input type="text" name="isbn" value="<?php if(isset($T['isbn'])) echo $T['isbn'] ?>" size="25" /> et son genre <?php echo gen_select($GENRES, 'genre', true) ?></p>
	<p class="center"><input class="button" type="submit" value="Chercher" /></p>
	
	<div class="info_message mt60">
	<p><strong>Aide</strong> Le code ISBN est un numéro à 10 ou 13 chiffres présent sur tous les livres. Il est écrit sur le dos du livre. Si vous ne trouvez pas l'ISBN, entrez simplement le numéro en dessous du code-barre du livre. Voici un exemple d'ISBN sur un livre :</p>
	<p class="center"><img src="style/isbn.png" alt="INBN" /></p>
	</div>
</form>
<?php }


////////////////////////////////////////////
// Ajout d'un livre : formulaire complet

elseif (isset($T['form2'])) { ?>
<div class="demandeform first" id="add_livre">
Entrez les informations relatives à votre livre :<br/>
<em>(Les informations suivies d'une * sont obigatoires)</em>
<form action="ajouter-un-livre.html" method="post">
	<table>
		<tr>
			<td class="pb07"><label for="i_titre">Titre (*)</label></td>
			<td class="pb07"><input id="i_titre" type="text" name="titre" <?php if($T['isbn']) echo 'disabled="disabled"'; ?> value="<?php echo htmlspecialchars($T['book']['titre']) ?>" /></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_auteur">Auteur (*)</label></td>
			<td class="pb07"><input id="i_auteur" type="text" name="auteur" <?php if($T['isbn']) echo 'disabled="disabled"'; ?> value="<?php echo htmlspecialchars($T['book']['auteur']) ?>" /></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_genre">Genre (*)</label></td>
			<td class="pb07"><?php echo gen_select($GENRES, 'genre', true, $T['book']['genre'], $T['isbn']) ?></td>
		</tr>
		<tr>
			<td><label for="i_pubdate">Année de publication</label><br/><em class="small">(Format XXXX)</em></td>
			<td class="pb07"><input id="i_pubdate" type="text" <?php if($T['isbn']) echo 'disabled="disabled"'; ?> name="pubdate" value="<?php echo $T['book']['pubdate'] ?>" /></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_resume">Résumé</label></td>
			<td class="pb07"><textarea id="i_resume" name="resume" <?php if($T['isbn']) echo 'disabled="disabled"'; ?>><?php echo htmlspecialchars($T['book']['resume']) ?></textarea></td>
		</tr>
		<tr>
			<td>Couverture
			<?php if($T['isbn'] && $T['book']['thumbs']['g'] && $T['book']['thumbs']['ol']) { ?><br/><em class="small">(Choisissez la couverture souhaitée)</em><?php } ?>
			</td>
			<td id="miniatures">
				<?php if($T['isbn']) { ?>
					<?php if($T['book']['thumbs']['g']) { ?>
						<input id="i_thmb1" type="radio" class="radio" name="thumb_type" value="g" /> <label for="i_thmb1">
						<img src="http://books.google.fr/books?id=<?php echo $T['book']['googleid'] ?>&printsec=frontcover&img=1&zoom=5" height="35" />
						</label><br/>
					<?php }
					if($T['book']['thumbs']['ol']) { ?>
						<input id="i_thmb2" type="radio" class="radio" name="thumb_type" value="ol" /> <label for="i_thmb2">
						<img src="http://covers.openlibrary.org/b/isbn/<?php echo $T['book']['isbn'] ?>-S.jpg" height="35" />
						</label><br/>
					<?php } ?>
				<input id="i_thmb3" type="radio" class="radio" name="thumb_type" value="url" />
				<?php } ?>
				<input id="i_thumb_url" type="text" name="thumb_url" value="<?php echo $T['book']['thumbs']['url'] ?>" /><br/>
				<em class="small">Si vous le voulez, entrez ici un lien vers l'image de la couverture du livre.</em>
			</td>
		</tr>
	</table>
	
	<p class="center"><input class="button" type="submit" name="form2" value="Ajouter" /></p>
</form>
</div>
<?php }




////////////////////////////////////////////
// MAJ de la couverture

elseif (isset($T['form3'])) { ?>
<div class="info_message">
	<h2>Livre en cours d'édition :</h2>
	<table>
		<tr><td rowspan="3"><img class="img-m" src="<?php echo get_thumb($T['book']['id'], 'm') ?>" /></td>
			<td>Titre :</td><td><?php echo $T['book']['titre'] ?></td></tr>
		<tr><td>Auteur :</td><td><?php echo $T['book']['auteur'] ?></td></tr>
		<tr><td>Genre :</td><td><?php echo $T['book']['genre'] ?></td></tr>
	</table>
</div>

<div class="demandeform first" id="maj_livre">
	<form action="mes-livres-maj-<?php echo $T['book']['id'] ?>.html" method="post">
		<p>Entrez le lien de la couverture que vous voulez associer à votre livre (100px de hauteur minimum) :</p>
		<p class="center"><input type="text" id="i_thumb_url" name="thumb_maj" value="" /></p>
		<p class="center"><input class="button" type="submit" value="Mettre à jour" /></p>
	</form>
</div>
<?php }


/////////////////////////////////////
// Liste de mes livres

else {

if (!empty($T['book_added'])) { ?>
<div class="info_message">
Votre livre a été ajouté :
<table>
	<tr><td rowspan="5"><img class="img-m" src="<?php echo get_thumb($T['book_added']['id'], 'm') ?>" /></td>
	    <td>Titre :</td><td><?php echo $T['book_added']['titre'] ?></td></tr>
	<tr><td>Auteur :</td><td><?php echo $T['book_added']['auteur'] ?></td></tr>
	<tr><td>Date de publication :</td><td><?php echo $T['book_added']['pubdate'] ?></td></tr>
	<tr><td>Genre :</td><td><?php echo $T['book_added']['genre'] ?></td></tr>
	<tr><td>Résumé :</td><td><?php echo $T['book_added']['resume'] ?></td></tr>
</table>
</div>
<?php } ?>

<p>Voici la liste des livres que vous avez ajouté au site. Un livre prêté est signalé par un fond de couleur plus foncé, et un lien vous revoie directement sur la discussion concernant son prêt.</p>

<p class="center"><a class="bigbutton" href="ajouter-un-livre.html">Ajouter un livre</a></p>
<ul id="meslivres">
	<?php $first = true; foreach ($T['livres'] as $livre) { ?>
	<li class="<?php if ($livre['id_demande']) echo 'lent'; if($first) { echo ' first'; $first = false; } ?>">
		<span class="buttons">
			<?php if ($livre['id_demande']) { ?><a href="<?php echo 'demande-'.$livre['id_demande'].'-'.txt2url($livre['titre']).'.html'; ?>" title="Voir la demande"><img src="style/back.png" alt="Demandé" /></a><?php } ?>
			<a href="mes-livres-maj-<?php echo $livre['id']; ?>.html" title="Mettre à jour la couverture"><img src="style/maj.png" alt="Maj." /></a>
			<a href="mes-livres-del-<?php echo $livre['id']; ?>.html" class="confirm" title="Supprimer le livre"><img src="style/del.png" alt="Suppr." /></a>
		</span>
		<img class="img-s" src="<?php echo get_thumb($livre['id'], 's') ?>" />
		<a href="<?php echo 'livre-'.$livre['id'].'-'.txt2url($livre['titre']).'.html'; ?>"><?php echo $livre['titre'] ?></a>
	</li>
	<?php } ?>
</ul>
<?php if ($first) echo 'Aucun livre ajouté.'; ?>

<?php } ?>