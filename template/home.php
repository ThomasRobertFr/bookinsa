<div class="homepic"><img src="style/home.png" alt="" /></div>

<h2>BookINSA, qu'est ce que c'est ?</h2>

<p><strong>BookINSA</strong> vous permet de vous prêter simplement des livres entre INSAïens. Un bon moyen de découvrir de nouveau livres simplement&nbsp;!</p>

<?php if (!$user) { ?><p>Pour utiliser pleinement le site, il vous suffit de cliquer sur le bouton &laquo;&nbsp;Connexion&nbsp;&raquo;, de vous connecter grâce à votre <strong>compte Facebook</strong><sup>*</sup> (vous n'avez donc pas besoin de vous inscrire). Vous pourrez alors <strong>ajouter les livres</strong> que vous voulez bien prêter dans la page &laquo;&nbsp;Mes livres&nbsp;&raquo;, mais également demander à <strong>emprunter les livres d'autres élèves en toute simplicité</strong>.</p><?php } else { ?>
<p>Maintenant que vous êtes connecté n'hésitez pas à <strong><a href="mes-livres.html">ajouter vos livres</strong></a> dans la page &laquo;&nbsp;Mes livres&nbsp;&raquo;, ou à trouver votre prochaine lecture dans la <a href="livres.html">liste des livres disponibles</a>.</p>
<?php } ?>

<p>Et pour avoir un aperçu de comment le site fonctionne, n'hésitez pas à jeter un œil à notre <a href="faq.html">FAQ</a>&nbsp;!</p>

<?php if (!$user) { ?><p><em class="small"><sup>*</sup> Pour les plus inquiets, sachez que vous ne nous donnez pas vos identifiants Facebook, vous les donnez à Facebook qui nous renvoie aussi votre nom et prénom.</em></p><?php } ?>

<h2 class="mt40">Derniers livres ajoutés sur BookINSA&nbsp;:</h2>

<div class="livres clear">
	<div class="livres_top"></div>
	<div class="livres_cont">
	<?php $i = 0; foreach($T['livres'] as $livre) {
	if ($i % 3 == 0 && $i != 0) { ?><div class="clear"></div></div><div class="livres_cont"><?php } ?>
	<a href="<?php echo 'livre-'.$livre['id'].'-'.txt2url($livre['titre']).'.html'; ?>" class="book_el3 book_el<?php if ($livre['id_demande']) echo ' lent' ?>">
		<img class="img-m" src="<?php echo get_thumb($livre['id'], 'm') ?>" />
		<h4><?php echo $livre['titre'] ?><br/>
		<em><?php echo $livre['auteur'] ?>
		<?php if ($livre['id_demande']) echo '<br/>Livre prêté' ?></em></h4>
	</a>
	<?php $i++; } ?>
	<a href="livres.html" class="book_el book_el3">
		<img class="img-m" src="style/livres.png" />
		<h4>Voir plus de livres...</h4>
	</a>
	<div class="clear"></div></div>
	<div class="livres_bottom"></div>
</div>