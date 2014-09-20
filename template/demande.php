<div class="book_el">
	<img class="img-m" src="<?php echo get_thumb($T['demande']['id'], 'm') ?>" />
	<h4 class="title"><?php echo $T['demande']['titre'] ?><br/>
	<em>Livre de <?php echo $T['users'][$T['demande']['to']] ?> demandé par <?php echo $T['users'][$T['demande']['from']] ?></em></h4>
	<div class="etat <?php echo $T['demande']['etat'] ?>"><?php echo $STATES[$T['demande']['etat']] ?></div>
</div>


<h2>Messages</h2>

<?php $first = true; foreach($T['messages'] as $message) { ?>
<div class="message<?php if ($first) { echo ' first'; $first = false; } ?>">
	<div class="img"><img src="https://graph.facebook.com/<?php echo $message['message_from']; ?>/picture?type=square" /></div>
	<div class="msg">
		<div class="date" title="<?php echo my_date($message['timestamp'], true) ?>"><?php echo my_date($message['timestamp']) ?></div>
		<div class="name"><?php echo $T['users'][$message['message_from']] ?></div>
		<div class="text">
		<?php echo nl2br($message['message']); ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="demandeform">
<form action="<?php echo 'demande-'.$T['demande']['id_demande'].'-'.txt2url($T['demande']['titre']).'.html'; ?>" method="post">
	<textarea name="message"></textarea><br/>
	<div class="fltr"><input type="submit" value="Répondre" class="button" /></div>
	<?php if ($T['recue']) { if ($T['demande']['etat'] == 'attente') { ?>
	<input type="checkbox" name="accept" /> Accepter la demande.<br/>
	<input type="checkbox" name="decline" /> Refuser la demande.<br/>
	<?php } elseif($T['demande']['etat'] == 'prete') { ?>
	<input type="checkbox" name="rendu" /> Indiquer le livre comme rendu.<br/>
	<?php } } ?>
	<div class="clear"></div>
</form>
</div>