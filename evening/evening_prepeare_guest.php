<div id='AppliedEvening'>
<? if ($EveningData['ready'] === false):?>
	На ближайшее время игры не запланированы! Загляните к нам позднее!
<?else:?>
	<span class='red_underline'><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></span><br>
	<div id='Part_Caption'>Учасники:</div>
	<div id='Part_List'>					
	<? if (count($EveningData['players']) <= 11) : ?><div class='one_column'><?
		for ($x=0; $x<count($EveningData['players']); $x++):
			$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')'?>
			<div><span><?=$x+1?>. </span><?=$EveningData['players'][$x]['name'].($dop==='()' ? '' : $dop)?></div>
		<? endfor;?>
		</div>
	<? else : ?>
		<div class='two_columns one'><?
		for ($x=0; $x<count($EveningData['players']); $x++):
			$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')';
			if ($x==11) : ?></div><div class='two_columns two'><?endif?>
			<div><span><?=$x+1?>. </span><?=$EveningData['players'][$x]['name'].($dop==='()' ? '' : $dop)?></div>
		<?endfor;?>
		</div>
	<? endif ?>
	</div>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>