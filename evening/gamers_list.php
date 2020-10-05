<div class="evening-active__gamers-array">
<?	$all_players = $engine->GetAllGamers($EveningID);
	foreach($all_players as $id=>$name):?>
		<span data-action-type="toggle-gamer-in-table" data-player-id='<?=$id?>' class="player_name <?=in_array($name,array_slice($tmp,0,10),true) ? ' selected' : (strpos($name,'tmp_user') !== false ? ' tmp_user' : '')?>"><?=$name?><span data-action-type="remove-gamer">X</span></span>&nbsp;
	<?endforeach;?>
</div>