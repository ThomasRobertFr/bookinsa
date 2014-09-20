<form id="livres_filters" action="livres.html" method="get">
	<table>
		<tr>
			<th rowspan="2"><h2>Filtrer les résultats :</h2></th>
			<th>Catégorie :</th>
			<th>Trier par :</th>
			<th>Rechercher :</th>
			<th></th>
		</tr>
		<tr>
			<td><?php echo gen_select($GENRES, 'genre', true, $T['genre']) ?></td>
			<td><?php echo gen_select($SORTS, 'sort', false, $T['sort']) ?></td>
			<td><input type="text" name="search" value="<?php echo $T['search'] ?>"> dans <?php echo gen_select($SEARCH_FIELDS, 'in', false, $T['in']) ?></td>
			<td><input type="submit" class="button" value="Valider"></td>
		</td>
	</table>
</form>

<div class="num_pages">
<?php echo liste_pages($T['page'], $T['nbr_pages'], $T['url_debut'], $T['url_fin']) ?>
</div>

<div class="livres">
	<?php if (empty($T['livres'])) echo '<div class="livres_top"></div><div class="livres_cont no_book">Aucun livre trouvé.';
	else { ?>
	<div class="livres_top"></div>
	<div class="livres_cont">
	<?php $i = 0; foreach($T['livres'] as $livre) {
	if ($i % 3 == 0 && $i != 0) { ?><div class="clear"></div></div><div class="livres_cont"><?php } ?>
	<a href="<?php echo 'livre-'.$livre['id'].'-'.txt2url($livre['titre']).'.html'; ?>" class="book_el3 book_el">
		<img class="img-m" src="<?php echo get_thumb($livre['id'], 'm') ?>" />
		<h4><?php echo $livre['titre'] ?><br/>
		<em><?php echo $livre['auteur'] ?></em></h4>
		<?php if ($livre['id_demande']) echo '<div class="etat attente">Prêté</div>' ?>
	</a>
	<?php $i++; } } ?>
	<div class="clear"></div></div>
	<div class="livres_bottom"></div>
</div>

<div class="num_pages">
<?php echo liste_pages($T['page'], $T['nbr_pages'], $T['url_debut'], $T['url_fin']) ?>
</div>