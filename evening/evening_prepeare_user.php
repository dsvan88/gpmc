<div id='AppliedEvening'>
<? if ($EveningData['ready'] === false):?>
	На ближайшее время игры не запланированы! Загляните к нам позднее!
<?else: 
	$me = -1?>
	<span class='red_underline'><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></span><br>
	<div id='Part_Caption'>Учасники:</div>
		<div id='Part_List'>					
		<? if (count($EveningData['players']) <= 11) : ?>
			<div class='one_column'><?
				for ($x=0; $x<count($EveningData['players']); $x++):
					$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')';
					if ($EveningData['players'][$x]['id'] == $_SESSION['id']) $me = $x?>
					<div><span><?=$x+1?>. </span><a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['players'][$x]['id']?>' target='_blank'><?=$EveningData['players'][$x]['name']?></a><?=($dop !== '()' ? $dop : '')?></div>
				<? endfor;?>
			</div>
		<? else : ?>
			<div class='two_columns one'><?
				for ($x=0; $x<count($EveningData['players']); $x++):
					if ($x==11) : ?></div>
					<div class='two_columns two'><? endif;
					$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')';
					if ($EveningData['players'][$x]['id'] == $_SESSION['id']) $me = $x?>
					<div><span><?=$x+1?>. </span><a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['players'][$x]['id']?>' target='_blank'<?=$me !== -1 ? ' class="its_me"' : ''?>><?=$EveningData['players'][$x]['name']?></a><?=($dop !== '()' ? $dop : '')?></div>
				<?endfor;?>
				</div>
		<? endif ?>
		</div>
	<? if ($me === -1) :?>
		<span class='span_button' id='ApplyMyReg'><img src='<?=$settings['img']['apply']['value']?>'/>Я пойду!<img src='<?=$settings['img']['apply']['value']?>'/></span>
	<?else :?>
		<span class='span_button' id='CancelMyReg'><img src='<?=$settings['img']['cancel']['value']?>'/>Планы изменились, извините!<img src='<?=$settings['img']['cancel']['value']?>'/></span>
	<?endif?>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>