<script type='text/javascript'>
$(function(){
	$('.datepick').datetimepicker({timepicker:false,format:'d.m.Y',dayOfWeekStart : 1});
	$('#date_from,#date_to').change(function(){
		$.ajax({
			url:'templates/show_rating.php'
			, async: false
			, type:'POST'
			, data: 'd='+$('#date_from').val()+'&d2='+$('#date_to').val()
			, success: function(res) {
				$('#MainBody').html(res);
			}
		});
	});
});
</script>
<?
$q=floor(((int)date('m'))/3)-1;
$enum_q_begin=array('01.01','01.04','01.07','01.10');
$enum_q_end=array('01.04','01.07','01.10','01.01');
$date= isset($_POST['d']) ? $_POST['d'] : date('d.m.Y',strtotime($enum_q_begin[$q].'.'.date('Y')));
$date2= isset($_POST['d2']) ? $_POST['d2'] : date('d.m.Y',strtotime($enum_q_end[$q].'.'.date('Y')));
?>
<div class='RatingHeader'>Расчитать рейтинг:<br>
С&nbsp;<input class='datepick' id='date_from' value="<?=$date?>"/>&nbsp;по&nbsp;<input class='datepick' id='date_to' value="<?=$date2?>"/></div>
<?
$evenings = array();
$evenings = $engine->allEveningsGetData(strtotime($date),strtotime($date2));
$i = -1;
$g_IDs = '';
while(++$i<count($evenings))
{
	$g_IDs .= $evenings[$i]['games'].',';
	$p_IDs .= $evenings[$i]['players'];
}
if ($g_IDs == ''):
	?> За указанный период не было игр. Показывать нечего.<?
else :
	$rating = prep_rating_array($p_IDs);
	$games = $engine->GetAllGames(substr($g_IDs,0,-1));
	foreach($games as $num=>$game)
		calc_rating(json_decode(str_replace('»','"',$game['players']),true),json_decode(str_replace('»','"',$game['vars']),true),$rating);
	foreach($rating as $k=>$v)
		$sorting[$k] = $v['summ'];
	arsort($sorting);
	?>	<table class='Rating' border='2' bordercolor='#000000'><caption><i>Личный рейтинг игроков уровня: <b><?=$enum_rating[$ti]?></b></i></caption>
		<thead><tr><th>#<th>Игрок:</th><th>Побед<br>Доном:</th><th>Побед<br>Шерифом:</th><th>Побед<br>Мафией:</th><th>Побед<br>Мирным:</th><th>Игр<br>всего:</th><th>Побед<br>всего:</th><th>Лучший<br>ход</th><th>Лучший<br>игрок</th><th>Коэфициент<br>побед:</th><th>Общий<br>рейтинг:</th></tr>
		<?
		$i=0;
		foreach($sorting as $id=>$rate)
		{
			if ($rating[$id]['name'] === '') continue;
			?><tr id="<?=$id?>"><td><?=++$i?>.<td><?=$rating[$id]['name']?><td><?=$rating[$id]['win_don']?><td><?=$rating[$id]['win_she']?><td><?=$rating[$id]['win_maf']?><td><?=$rating[$id]['win_mir']?><td><?=$rating[$id]['games']?><td><?=$rating[$id]['winner']?><td><?=round($rating[$id]['bm'],2)?><td><?=round($rating[$id]['bm'],2)?><td><?=round($rating[$id]['q_win'],3)?><td><?=round($rate,3)?></tr><?
		}
		?></table><br><br><?
endif;