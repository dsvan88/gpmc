<form class="evening-booking" id="eveningRegisterForm" method="POST">
<? 
	$days = array( 'Воскресенье', 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота');
	$plan_tobe = array('','на 1-2 игры', 'на 2-3 игры', 'на 3-4 игры');
	if ($EveningData['ready'] !== false) 
	{
		$EveningData['time'] = date('H:i',$EveningData['date']);
		$EveningData['place'] = $EveningData['place'] === '0' ? array('id'=>'0','name'=>'','info'=>'') : $engine->GetPlaceByID($EveningData['place']);
		if ($EveningData['gamers'] !== '')
			$EveningData['gamers'] = $engine->GetGamersNames($EveningData['gamers'],true);
		if ($EveningData['gamers_info'] !== '')
			$EveningData['gamers_info'] = json_decode($EveningData['gamers_info'],true);
	}
	if ($EveningData['ready'] === false)
		$EveningData = array(
			'date'=>strtotime(date('d.m.Y 17:00')),
			'place' => [
				'name'=>'',
				'info' => ''
			]
		);
	
	if (!isset($EveningData['gamers']) || $EveningData['gamers'] === '')
		$EveningData['gamers'] = $engine->GetResidentsNames(11);
?>


<div class="evening-booking__date">
	<span class="field-label">Время:</span>
	<input class='datepick' type='text' name='eve_date' value='<?=date('d.m.Y H:i',$EveningData['date'])?>'/>
</div>
<div class="evening-booking__place">
	<span class="field-label">Место:</span>
	<input type='text' id="eveningPlace" name='eve_place' value='<?=$EveningData['place']['name']?>'/><br>
	<span class="field-label">Адрес: </span>
	<input type='text' name='eve_place_info' value='<?=$EveningData['place']['info']?>' placeholder='Адрес, веб-сайт'/>
</div>
<div class='evening-booking__buttons'>
	<span class='span_button' data-action-type='set-evening-data'>
		<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
		<?=isset($EveningData['id']) ? 'Изменить' : 'Подтвердить'?>!
		<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
	</span>
</div>
<hr>
<div class="evening-booking__participants" id="eveningGamersFields">
	<h2>Участники:</h2>
<?
	$i=-1;
	$max = $EveningData['gamers'] !== '' ? count($EveningData['gamers']) : 11;
	while(++$i < $max)
		include $root_path.'/templates/gamer-field.php';
?>
	</div>
	<div class='evening-booking__buttons'>
		<span class='span_button' data-action-type='add-gamers'>
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
			Добавить поле
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
		</span><br><br>
		<span class='span_button' data-action-type='approve-evening'>
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
			Утвердить!
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
		</span>
	</div>
</form>