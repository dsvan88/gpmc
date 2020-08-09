<?
if (!isset($root_path))
	$root_path = $_SERVER['DOCUMENT_ROOT'];
$engine_set = 'JSFUNC';
include $root_path.'/engine/engine.php'; 
$engine = new JSFunc();
//------------------------------------------------------------------------------- Основные значения
require $root_path.'/game/tech/vars_default.php';
$players = $a = $avatar = array();
$enum_roles=array('red','mafia','don','','sherif');
$enum_roles_rus=array('Мирный','Мафия','Дон','','Шериф');
$enum_rating=array('C','B','A');
//------------------------------------------------------------------------------- Загрузка данных игры
$game_id = (int) $_GET['g_id'];
$a = $engine->ResumeGame($game_id);
$EveningID = (int) $a['e_id'];
$players = json_decode(str_replace('\\','',$a['players']),true);
$vars = json_decode(str_replace('\\','',$a['vars']),true);
$a['bm'] = $vars['bm'] != '' ? implode(',',$vars['bm']) : '';
$a['num'] = $engine->GetGameNum($EveningID,$game_id);
//------------------------------------------------------------------------------- Получение некоторых данных о пользователях
$a['manager'] = $engine->GetPlayerName($a['manager']);
$tmp = $engine->GetPlayerData(array('id','gender','avatar'),array('id'=>explode(',',$a['g_ids'])),0);
for($x=0;$x<count($tmp);$x++)
	$avatar[$tmp[$x]['id']] = $tmp[$x]['avatar'] !== '' ? '/gallery/users/'.$tmp[$x]['id'].'/'.$tmp[$x]['avatar'] : $img_genders[$tmp[$x]['gender']];
//------------------------------------------------------------------------------- Загрузка игровой таблицы
if ($a['win']==='0')
{
	if (isset($_SESSION['id']) && ($_SESSION['id'] == $a['manager'] || $_SESSION['status'] > 1))
	{
		require $root_path.'/game/tech/timer.php';
		require $root_path.'/game/game_active.php';
	}
	elseif (isset($_SESSION['status']) && $_SESSION['status'] < 1)
		require $root_path.'/game/game_active_user.php';
	else
		require $root_path.'/game/game_active_guest.php';
}
else
	require $root_path.'/game/game_history.php';
//------------------------------------------------------------------------------- Подгружаем данные игры в саму игру и приводим значения в порядок
?>
<script type='text/javascript'>
id_game=<?=$game_id?>;
<? if (!isset($a['players'])) : ?>
var players = <?=str_replace('\\','',json_encode($players,JSON_UNESCAPED_UNICODE))?>;
<?else:?>
var players = <?=str_replace('\\','',$a['players'])?>;
<?endif?>
<?if ($a['txt'] != ''): ?> var prev_text = ['<?=str_replace('\\','',$a['txt'])?>'];<?endif?>
<?if ($a['vars'] != ''): ?>vars = <?=str_replace('\\','',$a['vars'])?>;
<?else:?>vars = <?=json_encode($def_vars)?>;<?endif?>
var reasons = <?=json_encode($reasons,JSON_UNESCAPED_UNICODE)?>;
load = true;
load_state();
</script>
<div class='events hide'>
	<input type="button" id="SaveEnd" class="menu_button" value="Сохранить итог"/>
</div>
<span id="OnVote" class="ingame_event hide">На голосовании игроки под номерами:</span>
<div id="ShowLog_<?=$game_id?>" class="LogHeader">+ Открыть лог игры</div>
<div id="Log_<?=$game_id?>" class='hide'><?=str_replace(array('BR','HR'),array(' ','<hr>'),$engine->GetGameLog($game_id))?></div>
<div id="best_move" class = "ingame_event<?=$bm!='' ? '' : ' hide'?>">
	<span class="ingame_event">Лучший ход:&nbsp;</span><span id='bm' class="ingame_event">&nbsp;<?=$bm?></span><br>
</div>