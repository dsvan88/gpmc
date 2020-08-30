<? /*
if (!isset($EveningData))
	$EveningID = $engine->SetEveningID(strtotime(isset($_POST['eve_date']) ? $_POST['eve_date'] : date('d.m.Y')),$players['ids']);
else 
	$EveningID = $EveningData['id'];
$players = $engine->GetRandomPlayers(10,$EveningID);
?>
<script>EveningID=<?=$EveningID?></script>
<? require $root_path.'/game/game_register_form.php'?>
<br>
<div class='SepDiv'>
	<button id="ShufleGamers" class="menu_button">Перемешать игроков</button>
</div>
<br>
<hr/>
<? require $root_path.'/evening/evening_gamers_list.php'?>
<hr/>
<div class='SepDiv'><button id='AddPlayersToArray'>Добавить игрока</button></div>
<? require $root_path.'/game/check_game_in_progress.php';*/?>

<div class="evening-booking__guest">
<? if ($EveningData['ready'] === false):?>
	На ближайшее время игры не запланированы! Загляните к нам позднее!
<?else:?>
	<h2 class="evening-booking__guest-title"><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></h2><br>
	<h3 class="evening-booking__guest-subtitle">Учасники:</h3>
	<ol class="evening-booking__guest__participants-list">					
		<?
		for ($x=0; $x<count($EveningData['gamers']); $x++):
			$dop = '('.($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] ? 'к '.$EveningData['gamers_info'][$x]['arrive'] : '').($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] && $EveningData['gamers_info'][$x]['duration'] > 0 ? ', ' : '').($EveningData['gamers_info'][$x]['duration'] > 0 ? $plan_tobe[$EveningData['gamers_info'][$x]['duration']] : '').')'?>
			<li class='evening-booking__guest__participants-list__item'>
				<?=$EveningData['gamers'][$x]['name'].($dop==='()' ? '' : $dop)?>
			</li>
		<? endfor?>
	</ol>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>