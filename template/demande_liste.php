<h2>Demandes de prêt reçues</h2>

<div class="livres">
	<div class="livres_top"></div>
	<?php
	
	$i = 0; $prev = ''; $first = true; $hide = false;
	
	foreach($T['d_recues'] as $d) { if (!empty($d['titre'])) {
	
	// si on change de catégorie
	if ($d['messages'] && $prev != 'newmess' || !$d['messages'] && $d['etat'] != $prev) {
		$prev = ($d['messages']) ? 'newmess' : $d['etat'];
		$i = 0;
	}	
	
	// changement de ligne
	if ($i % 2	== 0) {
		if ($first && !$hide && ($prev == 'rendu' || $prev == 'refuse')) { echo '<div class="livres_cont no_book">Aucune demande reçue &laquo;&nbsp;ouverte&nbsp;&raquo;.'; $first = false; }	// si on avait enccore rien affiché et qu'on va masquer
		if (!$first) { echo '<div class="clear"></div></div>'; } else $first = false;
		if (!$hide && ($prev == 'rendu' || $prev == 'refuse')) {
			echo '<div class="show" style="display: none;"><a href="#"><img src="style/bas.png" alt="" /> Afficher les demandes refusées et les livres rendus <img src="style/bas.png" alt="" /></a></div><div class="hide">'; $hide = true;
		}
		echo '<div class="livres_cont">';
		if ($i == 0) echo '<div class="livres_fond_'.$prev.'"></div>';
	}
	
	?>
	<a href="<?php echo 'demande-'.$d['id_demande'].'-'.txt2url($d['titre']).'.html' ?>" class="book_el">
		<img class="img-m" src="<?php echo get_thumb($d['id'], 'm') ?>" />
		<h4 class="title"><?php echo $d['titre'] ?><br/>
		<em>Demandé par <?php echo $d['prenom'].' '.$d['nom'] ?></em></h4>
		<div class="etat <?php echo $d['etat'] ?>"><?php echo $STATES[$d['etat']] ?></div>
		<?php if ($d['messages']) { ?><div class="newmess"><?php echo $d['messages'] ?></div><?php } ?>
	</a>
	<?php $i++; } } ?>
	<?php if (!$i) echo '<div class="livres_cont no_book">Aucune demande reçue.'; ?>
	<div class="clear"></div></div>
	<?php if ($hide) { ?></div><?php } ?>
	<div class="livres_bottom"></div>
</div>


<h2 class="clear">Demandes de prêt envoyées</h2>

<div class="livres">
	<div class="livres_top"></div>
	<?php
	
	$i = 0; $prev = ''; $first = true; $hide = false;
	
	foreach($T['d_envoyees'] as $d) { if (!empty($d['titre'])) {
	
	// si on change de catégorie
	if ($d['messages'] && $prev != 'newmess' || !$d['messages'] && $d['etat'] != $prev) {
		$prev = ($d['messages']) ? 'newmess' : $d['etat'];
		$i = 0;
	}	
	
	// changement de ligne
	if ($i % 2	== 0) {
		if ($first && !$hide && ($prev == 'rendu' || $prev == 'refuse')) { echo '<div class="livres_cont no_book">Aucune demande envoyée &laquo;&nbsp;ouverte&nbsp;&raquo;.'; $first = false; }	// si on avait enccore rien affiché et qu'on va masquer
		if (!$first) { echo '<div class="clear"></div></div>'; } else $first = false;
		if (!$hide && ($prev == 'rendu' || $prev == 'refuse')) {
			echo '<div class="show2" style="display: none;"><a href="#"><img src="style/bas.png" alt="" /> Afficher les demandes refusées et les livres rendus <img src="style/bas.png" alt="" /></a></div><div class="hide2">'; $hide = true;
		}
		echo '<div class="livres_cont">';
		if ($i == 0) echo '<div class="livres_fond_'.$prev.'"></div>';
	}
	
	?>
	<a href="<?php echo 'demande-'.$d['id_demande'].'-'.txt2url($d['titre']).'.html' ?>" class="book_el">
		<img class="img-m" src="<?php echo get_thumb($d['id'], 'm') ?>" />
		<h4 class="title"><?php echo $d['titre'] ?><br/>
		<em>Appartenant à <?php echo $d['prenom'].' '.$d['nom'] ?></em></h4>
		<div class="etat <?php echo $d['etat'] ?>"><?php echo $STATES[$d['etat']] ?></div>
		<?php if ($d['messages']) { ?><div class="newmess"><?php echo $d['messages'] ?></div><?php } ?>
	</a>
	<?php $i++; } } ?>
	<?php if (!$i) echo '<div class="livres_cont no_book">Aucune demande envoyée.'; ?>
	<div class="clear"></div></div>
	<?php if ($hide) { ?></div><?php } ?>
	<div class="livres_bottom"></div>
</div>