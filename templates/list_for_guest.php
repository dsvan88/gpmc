<div class="evening-booking__guest">
<? if ($EveningData['ready'] === false):?>
	<h2>На ближайшее время игры не запланированы! Загляните к нам позднее!</h2>
<?else:?>
	<h2 class="evening-booking__guest-title"><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></h2><br>
	<h3 class="evening-booking__guest-subtitle">Учасники:</h3>
	<ol class="evening-booking__guest__participants-list">					
		<div class="guest-list-column">			
		<?
		for ($x=0; $x<count($EveningData['gamers']); $x++):
			if ($x>0 && $x%13===0):?>
				</div>
				<div class="guest-list-column">
			<?endif;	
			$dop = '('.($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] ? 'к '.$EveningData['gamers_info'][$x]['arrive'] : '').($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] && $EveningData['gamers_info'][$x]['duration'] > 0 ? ', ' : '').($EveningData['gamers_info'][$x]['duration'] > 0 ? $plan_tobe[$EveningData['gamers_info'][$x]['duration']] : '').')'?>
			<li class='evening-booking__guest__participants-list__item'>
				<span><?=$EveningData['gamers'][$x]['name'].(strlen($dop) > 7 ? $dop : '')?></span>
			</li>
		<? endfor?>
		</div>
	</ol>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>