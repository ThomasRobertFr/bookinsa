<?php if (isset($_GET['js'])) { if (!empty($T['vote'])) {
	
	echo json_encode(array(
		'text' => $T['vote'],
		'size' => $T['vote_size']
	));
	
} } else { ?>
<table class="divcenter book-head">
	<tr>
		<td><img class="img-l" src="<?php echo get_thumb($T['livre']['id'], 'l') ?>" /></td>
		<td>
			<h3><?php echo $T['livre']['titre'] ?></h3>
			<h4>Par <a href="livres.html?search=<?php echo urlencode($T['livre']['auteur']) ?>&in=auteur"><?php echo $T['livre']['auteur'] ?></a><?php echo $T['livre']['pubdate_head'] ?></h4>
			<em>Ajouté par <a href="<?php echo 'profil-'.$T['livre']['id_owner'].'-'.txt2url($T['livre']['prenom'].' '.$T['livre']['nom']).'.html' ?>"><?php echo $T['livre']['prenom'].' '.$T['livre']['nom'] ?></a></em><br/>
			<?php if ($T['livre']['id_demande']) { ?><em class="small">Livre en cours de prêt.</em><br/><?php } ?>
			<br/>
			Genre : <a href="livres.html?genre=<?php echo $T['livre']['genre'] ?>"><?php echo $T['livre']['genre_txt'] ?></a><br/>
			Note :
			<div class="stars">
				<div class="stars_full" style="width: <?php echo $T['livre']['note'] * 16 ?>px"></div>
				<a class="rate_link" id="rate_1" href="?vote=1"></a>
				<a class="rate_link" id="rate_2" href="?vote=2"></a>
				<a class="rate_link" id="rate_3" href="?vote=3"></a>
				<a class="rate_link" id="rate_4" href="?vote=4"></a>
				<a class="rate_link" id="rate_5" href="?vote=5"></a>
			</div>
			<span id="vote_result"> (<?php echo plur($T['livre']['note_votes'], 'vote', 'votes'); ?>)</span>
			<input type="hidden" id="stars_def" value="<?php echo $T['livre']['note'] * 16 ?>" />
			<input type="hidden" id="livre_id" value="<?php echo $T['livre']['id'] ?>" />
		</td>
		<td class="sharelinks">
			<?php if($T['livre']['id_owner'] != $user && !$T['livre']['id_demande']) { ?>
				<p class="big"><a class="bigbutton" href="<?php echo 'demande-livre-'.$T['livre']['id'].'-'.txt2url($T['livre']['titre']).'.html'; ?>">Demander&nbsp;le&nbsp;prêt</a></p><?php } ?>
			<?php if($admin) { ?><p class="big"><a class="button confirm" href="index.php?p=livre&del&id=<?php echo $T['livre']['id'] ?>">Supprimer le livre</a></p><?php } ?>
			
			<p>
				<a href="http://www.facebook.com/sharer.php?u=<?php echo $T['s_url'] ?>&t=<?php echo $T['s_title'] ?>" target="_blank">
				<div class="fbshare">
					<div class="fblogo"></div>
					<div class="fbtext">Partager</div>
				</div>
				</a>
				<div class="clear"></div>
			</p>

			<p>
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $T['s_url'] ?>&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;font=arial&amp;layout=button_count" style="overflow: hidden; border: 0px none; width: 90px; height: 25px;"></iframe>
			</p>

			<p>
			<iframe style="width: 110px; height: 20px;" src="http://platform.twitter.com/widgets/tweet_button.1326407570.html#_=1327240477228&amp;_version=2&amp;count=horizontal&amp;counturl=<?php echo $T['s_url'] ?>&amp;enableNewSizing=false&amp;id=twitter-widget-0&amp;lang=fr&amp;original_referer=<?php echo $T['s_url'] ?>&amp;size=m&amp;text=<?php echo $T['s_title'] ?>" allowtransparency="true" frameborder="0" scrolling="no"></iframe>
			</p>

			<p>
			<iframe allowtransparency="true" hspace="0" src="https://plusone.google.com/_/+1/fastbutton?url=<?php echo $T['s_url'] ?>&amp;size=medium&amp;count=true&amp;annotation=&amp;hl=fr-FR&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DXsa0GTewdqg.en.%2Fsv%3D1%2Fam%3D%21KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327240479214&amp;parent=<?php echo $T['s_url'] ?>&amp;rpctoken=53295810&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="position: static; left: 0pt; top: 0pt; width: 90px; margin: 0px; border-style: none; height: 20px;" tabindex="-1" vspace="0" title="+1" frameborder="0" scrolling="no" width="100%"></iframe>
			</p>
		</td>
	</tr>
</table>

<?php if (!empty($T['livre']['resume'])) { ?>
<div class="book-block first mt40">
	<h2>Résumé</h2>
	<?php echo $T['livre']['resume'] ?>
</div>
<?php } ?>

<div class="book-block<?php if (empty($T['livre']['resume'])) echo ' first mt40' ?>">
	<h2>Commentaires sur le livre</h2>
	<div class="fb-comments-p"><div class="fb-comments" data-href="<?php echo $T['fb_comments_url'] ?>" data-num-posts="1" data-width="600"></div></div>
</div>

<?php }