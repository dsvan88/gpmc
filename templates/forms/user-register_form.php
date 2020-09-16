<?
$output['html'] .= '
<form>
	<h2 class="title">Реєстрація користувача</h2>
	<div class ="input_row">
		<label>Логин</label>
		<input required name="username" type="text" class="input_name" value =""/>
	</div>
	<div class ="input_row">
		<label>Ник в игре</label>
		<input required name="player_name" type="text" class="input_name" value =""/>
	</div>
	<div class ="input_row">
		<label>Электронная почта</label>
		<input name="email" type="text" class="input_name" value =""/>
		</div>
	<div class ="input_row">
		<label>Пароль</label>
		<input required name="pass" type="password" class="input_name" value =""/>
	</div>
	<div class ="input_row">
		<label>Повторите пароль</label>
		<input required name="chk_pass" type="password" class="input_name" value =""/>
	</div>
	<div class ="input_row buttons">
		<button>Зарегистрировать</button>
	</div>
	<div class="input_row">
		<a class="modal-close">Назад</a>
	</div>
</form>';