<form id='Form_AdminLogin'>
	<div class ='input_row'><span><b>Логин</b></span><input type='text' name='login'/></div>
	<div class ='input_row'><span><b>Пароль</b></span><input type='password' name='pass'/></div>
	<input type='hidden' name='ap' value='<?=rand(1,1000)?>'/>
	<div class ='input_row'><button id ='LogInButton'>Войти</button></div>
	* Логин - только имя пользователя.
</form>