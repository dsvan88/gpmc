<div class="evening-booking__guest">
<? if ($EveningData['ready'] === false):?>
	<h2>На ближайшее время игры не запланированы! Загляните к нам позднее!</h2>
<?else:?>
	<h2 class="evening-booking__guest-title"><?=$days[date('N',$EveningData['date'])],' ',date('d.m.Y Время: H:i',$EveningData['date'])?></h2><br>
	<h3 class="evening-booking__guest-subtitle">Учасники:</h3>
	<ol class="evening-booking__guest__participants-list">
		<div class="guest-list-column">			
		<?
		$me = -1;
		for ($x=0; $x<count($EveningData['gamers']); $x++):
			if ($x>0 && $x%13===0):?>
				</div>
				<div class="guest-list-column">
			<?endif;	
			$dop = '('.($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] ? 'к '.$EveningData['gamers_info'][$x]['arrive'] : '').($EveningData['time'] !== $EveningData['gamers_info'][$x]['arrive'] && $EveningData['gamers_info'][$x]['duration'] > 0 ? ', ' : '').($EveningData['gamers_info'][$x]['duration'] > 0 ? $plan_tobe[$EveningData['gamers_info'][$x]['duration']] : '').')';
			if ($EveningData['gamers'][$x]['id'] == $_SESSION['id']) $me = $x
		?>
			<li class='evening-booking__guest__participants-list__item'>
			<? if ($me === $x):?>
				<a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['gamers'][$x]['id']?>' target='_blank' class="its-me"><?=$EveningData['gamers'][$x]['name']?></a><?=(strlen($dop) > 7 ? $dop : '')?>
				<a data-action-type='cancel-my-booking'>
					<?=$engine->checkAndPutImage($settings['img']['cancel']['value'])?>
				</a>
			<?else:?>
				<a href='http://<?=$_SERVER['SERVER_NAME']?>/?profile=<?=$EveningData['gamers'][$x]['id']?>' target='_blank'><?=$EveningData['gamers'][$x]['name']?></a><?=(strlen($dop) > 7 ? $dop : '')?>
			<?endif;?>
			</li>
		<? endfor?>
		</div>
	</ol>
	<? if ($me === -1) :?>
		<span class='span_button' data-form-type='apply-my-booking'>
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
			Я пойду!
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
		</span>
	<?else :?>
		<span class='span_button' data-action-type='cancel-my-booking'>
			<?=$engine->checkAndPutImage($settings['img']['cancel']['value'])?>
			Планы изменились, извините!
			<?=$engine->checkAndPutImage($settings['img']['cancel']['value'])?>
		</span>
	<?endif?>
	<div id='PlaceInfo'><?=$EveningData['place']['name'],' (',$EveningData['place']['info'],')'?></div>
<?endif?>
</div>