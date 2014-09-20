<table class="legendtab">
	<tr>
		<td>Légende :</td><td class="hl">Banni</td><td>&nbsp;</td><td class="hl2">Admin</td>
	</tr>
</table>

<table class="mt20 table">
	<tr>
		<th>ID</th>
		<th>Nom</th>
		<th>Mail</th>
		<th colspan="2">Ban/Unban</th>
		<th colspan="2">Admin</th>
	</tr>
<?php foreach($T['users'] as $auser) { ?>
	<tr<?php if ($auser['banned']) echo ' class="hl"'; if ($auser['admin']) echo ' class="hl2"';  ?>>
		<td class="center"><?php echo $auser['user_id']; ?></td>
		<td><?php echo $auser['prenom'].' '.$auser['nom']; ?></td>
		<td><?php echo $auser['email']; ?></td>
		<td class="center"><?php echo ($auser['banned']) ? 'X' : ''; ?></td>
		<td class="center"><?php if (!$auser['admin'] && !$auser['banned']) echo '<a href="?p=users&ban='.$auser['user_id'].'">Ban</a>'; elseif($auser['banned']) echo '<a href="?p=users&unban='.$auser['user_id'].'">Unban</a>' ?></td>
		<td class="center"><?php echo ($auser['admin']) ? 'X' : ''; ?></td>
		<td class="center"><?php if($user != $auser['user_id']) echo ($auser['admin']) ? '<a href="?p=users&unadmin='.$auser['user_id'].'">Unadmin</a>' : '<a href="?p=users&admin='.$auser['user_id'].'">Admin</a>'; ?></td>
	</tr>
<?php } ?>
</table>