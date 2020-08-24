<form class="evening-booking" id="EveningRegisterForm" method="POST">
<? if ($EveningData['ready'] === false)
	$EveningData = array(
			'date'=>strtotime(date('d.m.Y 17:00')),
			'place' => array(
				'name'=>'',
				'info' => ''
			),
			'players' => $engine->GetResidentsNames(11)
	);
	if ($EveningData['players'] === '')
		$EveningData['players'] = $engine->GetResidentsNames(11);
?>
<div class="evening-booking__date">
	<span class="field-label">Время:</span>
	<input class='datepick' type='text' name='eve_date' value='<?=date('d.m.Y H:i',$EveningData['date'])?>'/>
</div>
<div class="evening-booking__place">
	<span class="field-label">Место:</span>
	<input type='text' name='eve_place' value='<?=$EveningData['place']['name']?>'/><br>
	<span class="field-label">Адрес: </span>
	<input type='text' name='eve_place_info' value='<?=$EveningData['place']['info']?>' placeholder='Адрес, веб-сайт'/>
</div>
<div class='evening-booking__buttons'>
	<span class='span_button' id='ApplyEvening'>
		<?=$engine->checkAndPutImage($settings['img']['apply']['value'],'')?>
		<?=isset($EveningData['id']) ? 'Изменить' : 'Подтвердить'?>!
		<?=$engine->checkAndPutImage($settings['img']['apply']['value'],'')?>
	</span>
</div>
<hr>
<div class="evening-booking__participants">
	<h2>Участники:</h2>
<?
	$i=-1;
	$max = $EveningData['players'] !== '' ? count($EveningData['players']) : 11;
	while(++$i < $max)
		include $root_path.'/templates/gamer_field.php';
?>
	</div>
	<div class='evening-booking__buttons'>
		<span class='span_button' id='AddGamers'>
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?>
			Добавить поле
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?>
		</span><br><br>
		<span class='span_button' id='ApproveEvening'>
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'],'')?>
			Утвердить!
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'],'')?>
		</span>
	</div>
</form>