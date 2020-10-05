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
