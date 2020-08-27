<div id="GamersArray">
<?	$all_players = $engine->GetAllGamers($EveningID);
	foreach($all_players as $id=>$name):?>
		<span id='player_<?=$id?>' class="player_name <?=in_array($name,array_slice($tmp,0,10),true) ? ' selected' : (strpos($name,'tmp_user') !== false ? ' tmp_user' : '')?>"><?=$name?></span><span class='del' id='del_<?=$id?>'>X</span>&nbsp;
	<?endforeach;?>
</div>