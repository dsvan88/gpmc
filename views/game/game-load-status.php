<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.games.php';

    $users = new Users;
    $games = new Games;
    $gameLogs = new GameLogs;
	//------------------------------------------------------------------------------- Основные значения
	$enum_reasons = ['','Убит','Осуждён','4 Фола','Дисквал.'];
	$enum_roles = ['red','mafia','don','','sherif'];
	$enum_roles_rus = ['Мирный','Мафия','Дон','','Шериф'];
	$enum_rating = ['C','B','A'];
	$players = $gameData = $avatar = array();
	//------------------------------------------------------------------------------- Загрузка данных игры
	$gameId = (int) $_GET['gid'];

	$gameData = $games->gameResume($gameId);
	$eveningId = (int) $gameData['eid'];
	$players = json_decode($gameData['players'],true);
	$vars = json_decode($gameData['vars'],true);
 	$gameData['bestMove'] = $vars['bestMove'] != '' ? implode(',',$vars['bestMove']) : '';
	$gameData['num'] = $games->gameGetNumberOfEvening($eveningId,$gameId);
// 	//------------------------------------------------------------------------------- Получение некоторых данных о пользователях
	$gameData['manager'] = $users->userGetName($gameData['manager']);
// 	$usersData = $users->usersGetData(array('id','gender','avatar'),array('id'=>explode(',',$gameData['g_ids'])),0);
// 	for($x=0;$x<count($tmp);$x++)
// 		$avatar[$tmp[$x]['id']] = $tmp[$x]['avatar'] !== '' ? 
// 		'/gallery/users/'.$tmp[$x]['id'].'/'.$tmp[$x]['avatar'] : 
// 		$img_genders[$tmp[$x]['gender']];
// 	//------------------------------------------------------------------------------- Загрузка игровой таблицы
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
// 	<div id="ShowLog_<?=$gameId?>" class="LogHeader">+ Открыть лог игры</div>
// 	<div id="Log_<?=$gameId?>" class='hide'><?=str_replace(array('BR','HR'),array(' ','<hr>'),$engine->gameLogGet($gameId))?></div>
// 	<div id="best_move" class = "ingame_event<?=$bm!='' ? '' : ' hide'?>">
// 		<span class="ingame_event">Лучший ход:&nbsp;</span><span id='bm' class="ingame_event">&nbsp;<?=$bm?></span><br>
// 	</div>
// </div>
*/