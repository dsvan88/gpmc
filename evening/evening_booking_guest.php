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