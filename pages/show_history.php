<?
if (!isset($_GET['e']))
{
	if (isset($_GET['d']))
	{
		$date = $_GET['d'];
		$EveningID = $engine->eveningGetId(strtotime($date));
	}
	else
	{
		$tmp = $engine->lastEveningGetInfo();
		$date = date('d.m.Y',strtotime($tmp['date']));
		$EveningID = $tmp['id'];
		$history = $engine->GetAllGames($tmp['games']);
	}
}
else 	
{
	$EveningID = (int) $_GET['e'];
	$date = $engine->eveningGetDate($EveningID);
}

$p_EveningID = $EveningID-1;
if ($p_EveningID <= 0 || !$engine->eveningCheckId($p_EveningID))
	$p_EveningID = 0;

$a_EveningID = $EveningID+1;
if (!$engine->eveningCheckId($a_EveningID))
	$a_EveningID = 0;

?>
<script type='text/javascript'>
$(function(){
	$('.datepick').datetimepicker({timepicker:false,format:'d.m.Y',dayOfWeekStart : 1});
});
</script>
<div class='HystoryHeader'>Дата вечера игры:<br>
<div class='arrows<?=$p_EveningID > 0 ? '' : ' arr_disabled'?>' id='DayBefore_<?=$p_EveningID?>'><<</div><input class='datepick' id='DateGame' value="<?=$date?>"/><div class='arrows<?=$a_EveningID > 0 ? '' : ' arr_disabled'?>' id='DayAfter_<?=$a_EveningID?>'>>></div></div>
<?
if (!isset($history))
	$history = $engine->allGamesOfEvening($EveningID);
if ($history === false):
	?>Игр в этот вечер не найдено!<?
else :
	$s = $query = $ids = $roles = '';
	$a = array();
	$enum_roles=array('red','mafia','don','','sherif');
	$enum_roles_rus=array('Мирный','Мафия','Дон','','Шериф');
	$enum_rating=array('C','B','A');
	$enum_winner=array('','Мирный город','Команда мафии','Ничья');
	$reasons=array('','Убит','Осуждён','4 Фола','Дисквал.');
	$ti=0;
	foreach($history as $game):
		$game_id = $game['id'];
		$players = json_decode(str_replace(array('»','\\'),array('"',''),$game['players']),true);
		$vars = json_decode(str_replace(array('»','\\'),array('"',''),$game['vars']),true);
		$a['bm'] = 'Игрока №'.($vars['make_bm']+1).': '.implode(', ',$vars['bm']);
		$a['rating'] = $game['rating'];
		//------------------------------------------------------------------------------- Получение некоторых данных о пользователях
		$a['manager'] = $engine->getGamerName($game['manager']);
		$tmp = $engine->getGamerData(array('id','gender','avatar'),array('id'=>explode(',',$game['g_ids'])),0);
		for($x=0;$x<count($tmp);$x++)
			$avatar[$tmp[$x]['id']] = $tmp[$x]['avatar'] !== '' ? '/gallery/users/'.$tmp[$x]['id'].'/'.$tmp[$x]['avatar'] : $img_genders[$tmp[$x]['gender']];
		?>
		<div class='GameEnded'>
		<?include $root_path.'/game/game_history.php' ?>
		<a href='/?g_id=<?=$game_id?>'>Подробнее</a>
		</div><?
	endforeach;
endif;