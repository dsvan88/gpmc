<div class="evening-active__gamers-array">
<?	$all_players = $engine->playersGetAll($EveningID);
	foreach($all_players as $id=>$name):
		if (strpos($name,'tmp_user') !== false):?>
			<span data-form-type="rename-gamer" data-edit-target="<?=$id?>" class="player_name temp_username"><?=$name?><span data-action-type="remove-gamer">X</span></span>&nbsp;
		<?else:?>
			<span data-action-type="toggle-gamer-in-table" data-player-id="<?=$id?>" class="player_name <?=in_array($name,array_slice($tmp,0,10),true) ? ' selected' : ''?>"><?=$name?><span data-action-type="remove-gamer">X</span></span>&nbsp;
		<?endif?>
	<?endforeach;?>
</div>