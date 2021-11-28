<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.games.php';

$users = new Users;
$games = new Games;
$gameLogs = new GameLogs;

//------------------------------------------------------------------------------- Основные значения
$gameDefaultData = $games->gameGetDefaultData((int) $_GET['gid']); 

// 	//------------------------------------------------------------------------------- Получение некоторых данных о пользователях
// 	$usersData = $users->usersGetData(array('id','gender','avatar'),array('id'=>explode(',',$gameData['g_ids'])),0);
// 	for($x=0;$x<count($usersData);$x++)
// 		$avatar[$usersData[$x]['id']] = $usersData[$x]['avatar'] !== '' ? 
// 		'/gallery/users/'.$usersData[$x]['id'].'/'.$usersData[$x]['avatar'] : 
// 		$img_genders[$usersData[$x]['gender']];
// 	//------------------------------------------------------------------------------- Загрузка игровой таблицы
$output['{GAME_TIMER}'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/game-timer.html');
$output['{GAME_MAIN_TABLE}'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/game-table.html');
$output['{GAME_INDEX}'] = $gameDefaultData['gid'];
$output['{GAME_NUMBER}'] = $gameDefaultData['gnum'];
$output['{GAME_MANAGER}'] = $gameDefaultData['manager'];
$output['{GAME_PLAYERS_TABLE}'] = '';
for ($x=0; $x < count($gameDefaultData['players']); $x++) {
	$replace = [
		'{PLAYER_INDEX}' => $x,
		'{USER_INDEX}' => $gameDefaultData['players'][$x]['id'],
		'{PLAYER_NUMBER}' => $x+1,
		'{PLAYER_NAME}' => $gameDefaultData['players'][$x]['name'],
	];
	$output['{GAME_PLAYERS_TABLE}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/game-players-table-row.html'));
}
		
// 	if ($gameData['win']==='0')
// 	{
// 		if (isset($_SESSION['id']) && ($_SESSION['id'] == $gameData['manager'] || $_SESSION['status'] > 1))
// 		{
// 			require $_SERVER['DOCUMENT_ROOT'].'/game/tech/timer.php';
// 			require $_SERVER['DOCUMENT_ROOT'].'/game/active.php';
// 		}
// 		elseif (isset($_SESSION['status']) && $_SESSION['status'] < 1)
// 			require $_SERVER['DOCUMENT_ROOT'].'/game/game_active_user.php';
// 		else
// 			require $_SERVER['DOCUMENT_ROOT'].'/game/game_active_guest.php';
// 	}
// 	else
// 		require $_SERVER['DOCUMENT_ROOT'].'/game/game_history.php';
// 	//------------------------------------------------------------------------------- Подгружаем данные игры в саму игру и приводим значения в порядок
	/*?>
	<script type='text/javascript'>
	id_game=<?=$gameId?>;
	<? if (!isset($gameData['players'])) : ?>
	var players = <?=str_replace('\\','',json_encode($players,JSON_UNESCAPED_UNICODE))?>;
	<?else:?>
	var players = <?=str_replace('\\','',$gameData['players'])?>;
	<?endif?>
	<?if ($gameData['txt'] != ''): ?> var prev_text = ['<?=str_replace('\\','',$gameData['txt'])?>'];<?endif?>
	<?if ($gameData['vars'] != ''): ?>vars = <?=str_replace('\\','',$gameData['vars'])?>;
	<?else:?>vars = <?=json_encode($def_vars)?>;<?endif?>
	var reasons = <?=json_encode($reasons,JSON_UNESCAPED_UNICODE)?>;
	load = true;
	load_state();
	</script>
	*/
/* ?>
// 	<div class='events hide'>
// 		<input type="button" id="SaveEnd" class="menu_button" value="Сохранить итог"/>
// 	</div>
// 	<span id="OnVote" class="ingame_event hide">На голосовании игроки под номерами:</span>
// 	
// 	<div id="best_move" class = "ingame_event<?=$bm!='' ? '' : ' hide'?>">
// 		<span class="ingame_event">Лучший ход:&nbsp;</span><span id='bm' class="ingame_event">&nbsp;<?=$bm?></span><br>
// 	</div>
// </div>
*/