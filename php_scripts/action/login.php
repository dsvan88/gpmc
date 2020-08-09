<?php
$result=array(
	'error' => isset($_POST['ap']) ? $_POST['ap'] : 0,
	'txt' => ''
);
if (!isset($_POST['ap']) && $engine->LogIn($_POST['login'], md5(PASS_SALT.$_POST['pass'])) === false)
{
	$result['error'] = 1;
	$result['txt'] = 'Не верный логин/пароль!'.PHP_EOL.'Имя пользователя - только латинские буквы и числа'.PHP_EOL.'Ник в игре - только русские буквы и цифры'.PHP_EOL.'Проверьте правильность введения логина и пароля!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
elseif (isset($_POST['ap']) && $engine->AdminLogIn($_POST['login'], md5(PASS_SALT.$_POST['pass'])) === false)
{
	$result['error'] = 1;
	$result['txt'] = 'Не верный логин/пароль!'.PHP_EOL.'Имя пользователя - только латинские буквы и числа'.PHP_EOL.'Проверьте правильность введения логина и пароля!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
if (isset($_POST['ap'])) $_SESSION['ba'] = $_POST['ap'];
exit(json_encode($result,JSON_UNESCAPED_UNICODE));