<div class="book_el">
	<img class="img-m" src="<?php echo get_thumb($T['livre']['id'], 'm') ?>" />
	<h4 class="title"><?php echo $T['livre']['titre'] ?><br/>
	<em>Par <?php echo $T['livre']['auteur'] ?></em></h4>
	<div class="etat attente">Livre de <?php echo $T['livre']['prenom'].' '.$T['livre']['nom'] ?></div>
</div>


<div class="demandeform mt40 first">
<form action="<?php echo 'demande-livre-'.$T['livre']['id'].'-'.txt2url($T['livre']['titre']).'.html' ?>" method="post">
	Bonjour,<br/>
	<br/>
	J'aimerais t'emprunter ce livre.<br/>
	<br/>
	<textarea name="message">Ecrivez-votre message ici.</textarea><br/>
	<div class="fltr"><input type="submit" value="Envoyer" class="button" /></div>
	<div class="clear"></div>
</form>
</div>