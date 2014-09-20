<h2>Ses livres</h2>

<ul id="meslivres">
	<?php $first = true; foreach ($T['livres'] as $livre) { ?>
	<li class="<?php if (isset($livre['id_demande']) && $livre['id_demande']) echo 'lent'; if($first) { echo ' first'; $first = false; } ?>">
		<img class="img-s" src="<?php echo get_thumb($livre['id'], 's') ?>" />
		<a href="<?php echo 'livre-'.$livre['id'].'-'.txt2url($livre['titre']).'.html'; ?>"><?php echo $livre['titre'] ?></a>
	</li>
	<?php } ?>
</ul>
<?php if ($first) echo $T['user']['prenom'].' n\'a ajouté aucun livre.'; ?>

<h2 class="mt40">Commentaires sur l'utilisateur</h2>

<div class="book-block first">
	<div class="fb-comments-p"><div class="fb-comments" data-href="<?php echo $T['fb_comments_url'] ?>" data-num-posts="1" data-width="600"></div></div>
</div>

