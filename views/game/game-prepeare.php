<?php
$output['{SCRIPTS}'] .= '<script defer type="text/javascript" src="js/get_script.php/?js=evening"></script>';
$output['{NEAR_EVENING_BLOCK}'] = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/templates/game/game-prepeare.html');
$output['{EVENING_ID}'] = $EveningData['id'];

$output['{GAME_PREPEARE_PLAYERS_LIST}'] = '';

$i=-1;
$playerNames = [];
for ($x=0;$x<count($EveningData['participants_info']);$x++){
	if (strpos($EveningData['participants_info'][$x]['name'],'tmp_user') === false){
		$playerNames[++$i] = $EveningData['participants_info'][$x]['name'];
	}
}

$i=-1;
while(++$i<=9){
	$playerName = '';
	if (isset($playerNames[$i]))
		$playerName = $playerNames[$i];
	$output['{GAME_PREPEARE_PLAYERS_LIST}'] .= str_replace( ['{PLAYER_INDEX}','{PLAYER_NAME}'],[$i,$playerName],file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/game/game-prepeare-players-row.html') );
}
$registeredInGame = array_slice($playerNames,0,10);

$output['{GAME_PREPEARE_PLAYERS_ARRAY}'] = $output['{GAME_PREPEARE_PLAYERS_DATALIST}'] = '';
for ($x=0;$x<count($EveningData['participants_info']);$x++){
		if (strpos($EveningData['participants_info'][$x]['name'],'tmp_user') !== false){
			$output['{GAME_PREPEARE_PLAYERS_ARRAY}'] .= "
				<span data-action='participant-rename-form' data-edit-target='{$EveningData['participants_info'][$x]['id']}' class='player__name temp_username'>
					{$EveningData['participants_info'][$x]['name']}
					<span data-action='player-remove' class='player__remove'><i class='fa fa-times-circle'></i></span>
				</span>";
		}
		else {
			$output['{GAME_PREPEARE_PLAYERS_ARRAY}'] .= "
				<span data-action='player-toggle-in-table' data-player-id='$x' class='player__name ".(in_array($EveningData['participants_info'][$x]['name'],$registeredInGame,true) ? ' selected' : '')."'>{$EveningData['participants_info'][$x]['name']}<span data-action='player-remove' class='player__remove'><i class='fa fa-times-circle'></i></span>
				</span>";
			$output['{GAME_PREPEARE_PLAYERS_DATALIST}'] .= "
				<option>{$EveningData['participants_info'][$x]['name']}</option>";
		}
}