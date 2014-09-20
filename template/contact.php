<?php if ($T['form']) { ?>
<div class="demandeform first" id="add_livre">
Un problème ? Une question ? N'hésitez pas à nous contacter en remplissant ce formulaire :<br/>
<em class="small">(Tous les champs sont obligatoires)</em><br/>
<form action="contact.html" method="post">
	<table>
		<?php if (!$user) { ?>
		<tr>
			<td class="pb07"><label for="i_nom">Votre nom</label></td>
			<td class="pb07"><input id="i_nom" type="text" name="nom" value="<?php echo htmlspecialchars($T['nom']) ?>" /></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_email">Votre adresse e-mail</label></td>
			<td class="pb07"><input id="i_email" type="text" name="email" value="<?php echo htmlspecialchars($T['email']) ?>" /></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="pb07"><label for="i_sujet">Sujet du mail</label></td>
			<td class="pb07"><input id="i_sujet" type="text" name="sujet" value="<?php echo htmlspecialchars($T['sujet']) ?>" /></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_message">Message</label></td>
			<td class="pb07"><textarea id="i_message" name="message"><?php echo htmlspecialchars($T['message']) ?></textarea></td>
		</tr>
		<tr>
			<td class="pb07"><label for="i_captcha">Captcha</label></td>
			<td class="pb07 center"><img src="includes/captcha/captcha.php" id="captcha_img" alt="Captcha" /><br/>
			<input type="text" name="captcha" maxlength="6" id="i_captcha" value="" onload="this.value=''" style="width: 150px;" /> <input type="button" value="Je n'arrive pas à lire le code" onclick="document.getElementById('captcha_img').src = 'includes/captcha/captcha.php?rand='+(Math.random()*10000000000000000000);" style="width: 200px;"/><br/>
			<em class="small">Tapez le code affiché sur l'image</em>
			</td>
		</tr>
		<tr>
			<td class="pb07"></td>
			<td class="pb07 center"><input class="button" type="submit" value="Envoyer" /><td>
		</tr>
	</table>
</form>
</div>
<?php } ?>