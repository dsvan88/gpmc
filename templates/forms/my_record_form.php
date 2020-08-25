<?
$time = date('H:i',$engine->GetNearEveningData('date'));
$output['html'] .= '
<span class="caption">Регистрация нового участника</span>
<form id="Form_MyConfirm">
	<div class ="input_row">Начало игр, запланировано на <b>'.$time.'</b>!</div>
	<div class ="input_row"><span class="input_half_row"><b>Я буду к</b>:</span>
		<input name="plan_arrive" type="text" class="input_half_row timepicker" value="'.$time.'">
	</div>
	<div class ="input_row"><span class="input_half_row"><b>План</b> (1 игра ~ час):</span>
		<select name="plan_tobe" class="input_half_row">
			<option value="0">До конца!</option>
			<option value="1">На 1-2 игры</option>
			<option value="2">На 2-3 игры</option>
			<option value="3">На 3-4 игры</option>
		</select>
	</div>
	<div class ="input_row"><button id="AddMe">Записаться!</button></div>
	<span>* Уточните Ваши планы.</span>
</form>';