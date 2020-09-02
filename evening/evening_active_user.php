<?/*
<div id='AppliedEvening'>
	<?$me = -1?>
	<span class='red_underline'><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></span><br>
	<div id='Part_Caption'>Учасники:</div>
		<div id='Part_List'>					
		<? if (count($EveningData['gamers']) <= 11) : ?>
			<div class='one_column'><?
				for ($x=0; $x<count($EveningData['gamers']); $x++):
					$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')';
					if ($EveningData['gamers'][$x]['id'] == $_SESSION['id']) $me = $x?>
					<div><span><?=$x+1?>. </span><a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['gamers'][$x]['id']?>' target='_blank'><?=$EveningData['gamers'][$x]['name']?></a><?=($dop !== '()' ? $dop : '')?></div>
				<? endfor;?>
			</div>
		<? else : ?>
			<div class='two_columns one'><?
				for ($x=0; $x<count($EveningData['gamers']); $x++):
					if ($x==11) : ?></div>
					<div class='two_columns two'><? endif;
					$dop = '('.($EveningData['time'] !== $EveningData['times'][$x] ? 'к '.$EveningData['times'][$x] : '').($EveningData['time'] !== $EveningData['times'][$x] && $EveningData['tobe'][$x] > 0 ? ', ' : '').($EveningData['tobe'][$x] > 0 ? $plan_tobe[$EveningData['tobe'][$x]] : '').')';
					if ($EveningData['gamers'][$x]['id'] == $_SESSION['id']) $me = $x?>
					<div><span><?=$x+1?>. </span><a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['gamers'][$x]['id']?>' target='_blank'<?=$me !== -1 ? ' class="its_me"' : ''?>><?=$EveningData['gamers'][$x]['name']?></a><?=($dop !== '()' ? $dop : '')?></div>
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
</div>*/?>
<div class="evening-booking__guest">
<? if ($EveningData['ready'] === false):?>
	<h3>На ближайшее время игры не запланированы! Загляните к нам позднее!</h3>
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
			$dop = '('.($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] ? 'к '.$EveningData['gamers_info'][$x]['arrive'] : '').($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] && $EveningData['gamers_info'][$x]['duration'] > 0 ? ', ' : '').($EveningData['gamers_info'][$x]['duration'] > 0 ? $plan_tobe[$EveningData['gamers_info'][$x]['duration']] : '').')';
			if ($EveningData['gamers'][$x]['id'] == $_SESSION['id']) $me = $x
		?>
			<li class='evening-booking__guest__participants-list__item'>
				<a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['gamers'][$x]['id']?>' target='_blank'<?=$me !== -1 ? ' class="its_me"' : ''?>><?=$EveningData['gamers'][$x]['name']?></a><?=($dop !== '()' ? $dop : '')?>
			</li>
		<? endfor?>
		</div>
	</ol>
	<? if ($me === -1) :?>
		<span class='span_button' id='ApplyMyReg'><img src='<?=$settings['img']['apply']['value']?>'/>Я пойду!<img src='<?=$settings['img']['apply']['value']?>'/></span>
	<?else :?>
		<span class='span_button' id='CancelMyReg'><img src='<?=$settings['img']['cancel']['value']?>'/>Планы изменились, извините!<img src='<?=$settings['img']['cancel']['value']?>'/></span>
	<?endif?>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>