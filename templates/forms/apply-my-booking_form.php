<?
$time = date('H:i',$engine->nearEveningGetData('date'));
$output['html'] .= '
<form>
	<h2 class="title">Запись на вечер</h2>
	<h3 class="subtitle">Начало игр, запланировано на <span style="color:red">'.$time.'</span>!</h3>
	<div class ="input_row">
		<label>Я буду к:</label>
		<input name="arrive" type="text" class="timepicker" value="'.$time.'">
	</div>
	<div class ="input_row">
		<label>План (1 игра ~ час):</label>
		<select name="duration">
			<option value="0">До конца!</option>
			<option value="1">На 1-2 игры</option>
			<option value="2">На 2-3 игры</option>
			<option value="3">На 3-4 игры</option>
		</select>
	</div>
	<div class ="input_row buttons">
		<button>Записаться!</button>
	</div>
	<span>* Уточните Ваши планы.</span>
</form>';