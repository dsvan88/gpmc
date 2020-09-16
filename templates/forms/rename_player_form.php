<?
$time = date('H:i',$engine->GetNearEveningData('date'));
$output['html'] .= '
<form class="login-form">
	<h2 class="title">Переименование игрока</h2>
	<input type="hidden" name="old_name" value="'.$_POST['name'].'"/>
	<div class ="input_row">
		<label>Псевдоним:</label>
		<input name="new_name" type="text" class="input_gamer" value =""/>
	</div>
	<div class ="input_row buttons">
		<button>Переименовать</button>
	</div>
	<span>* Введите новый игровой ник игрока.<br>
	Он не должен совпадать с уже существующими</span>
</form>';