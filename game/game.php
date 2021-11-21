<div class="content__game">
	<?
	if (!isset($root_path))
		$root_path = $_SERVER['DOCUMENT_ROOT'];
	$engine_set = 'JSFUNC';
	include $root_path.'/engine/engine.php'; 
	$engine = new JSFunc();
	//------------------------------------------------------------------------------- Основные значения
	$enum_reasons = ['','Убит','Осуждён','4 Фола','Дисквал.'];
	$enum_roles = ['red','mafia','don','','sherif'];
	$enum_roles_rus = ['Мирный','Мафия','Дон','','Шериф'];
	$enum_rating = ['C','B','A'];
	$players = $gameData = $avatar = array();
	//------------------------------------------------------------------------------- Загрузка данных игры
	$game_id = (int) $_GET['g_id'];
	$gameData = $engine->ResumeGame($game_id);
	$EveningID = (int) $gameData['e_id'];
	
	$players = json_decode($gameData['players'],true);
	$vars = json_decode($gameData['vars'],true);

	$gameData['bestMove'] = $vars['bestMove'] != '' ? implode(',',$vars['bestMove']) : '';
	$gameData['num'] = $engine->GetGameNum($EveningID,$game_id);
	//------------------------------------------------------------------------------- Получение некоторых данных о пользователях
	$gameData['manager'] = $engine->getGamerName($gameData['manager']);
	$tmp = $engine->getGamerData(array('id','gender','avatar'),array('id'=>explode(',',$gameData['g_ids'])),0);
	for($x=0;$x<count($tmp);$x++)
		$avatar[$tmp[$x]['id']] = $tmp[$x]['avatar'] !== '' ? 
		'/gallery/users/'.$tmp[$x]['id'].'/'.$tmp[$x]['avatar'] : 
		$img_genders[$tmp[$x]['gender']];
	//------------------------------------------------------------------------------- Загрузка игровой таблицы
	if ($gameData['win']==='0')
	{
		if (isset($_SESSION['id']) && ($_SESSION['id'] == $gameData['manager'] || $_SESSION['status'] > 1))
		{
			require $root_path.'/game/tech/timer.php';
			require $root_path.'/game/active.php';
		}
		elseif (isset($_SESSION['status']) && $_SESSION['status'] < 1)
			require $root_path.'/game/game_active_user.php';
		else
			require $root_path.'/game/game_active_guest.php';
	}
	else
		require $root_path.'/game/game_history.php';
	//------------------------------------------------------------------------------- Подгружаем данные игры в саму игру и приводим значения в порядок
	/*?>
	<script type='text/javascript'>
	id_game=<?=$game_id?>;
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
	*/?>
	<div class='events hide'>
		<input type="button" id="SaveEnd" class="menu_button" value="Сохранить итог"/>
	</div>
	<span id="OnVote" class="ingame_event hide">На голосовании игроки под номерами:</span>
	<div id="ShowLog_<?=$game_id?>" class="LogHeader">+ Открыть лог игры</div>
	<div id="Log_<?=$game_id?>" class='hide'><?=str_replace(array('BR','HR'),array(' ','<hr>'),$engine->gameLogGet($game_id))?></div>
	<div id="best_move" class = "ingame_event<?=$bm!='' ? '' : ' hide'?>">
		<span class="ingame_event">Лучший ход:&nbsp;</span><span id='bm' class="ingame_event">&nbsp;<?=$bm?></span><br>
	</div>
</div>