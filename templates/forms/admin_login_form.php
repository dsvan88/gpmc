<form id='Form_AdminLogin'>
	<div class ='input_row'><span><b>Логин</b></span><input type='text' name='login'/></div>
	<div class ='input_row'><span><b>Пароль</b></span><input type='password' name='pass'/></div>
	<input type='hidden' name='ap' value='<?=rand(1,1000)?>'/>
	<div class ='input_row'><button id ='LogInButton'>Войти</button></div>
	* Логин - только имя пользователя.
</form>

<?
$output['html'] .= '
<form class="login-form admin">
	<h2 class="title">Авторизация</h2>
	<div class ="input_row">
		<label>Логин</label><input type="text" name="login" class="input_name"/>
	</div>
	<div class ="input_row">
		<label>Пароль</label><input type= "password" name="pass"/>
	</div>
	<div class ="input_row buttons">
		<button id ="LogInButton">Войти</button>
	</div>
	<input type="hidden" name="ap" value="'.rand(1,1000).'"/>
	<div class="input_row">
		<a id="frogetPassword">Забыли пароль?</a>
		<a id="registerNewUser">Регистрация</a>
	</div>
	* Логин - только имя пользователя.
</form>';
$output['size'] = '420,0';