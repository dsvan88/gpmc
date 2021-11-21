<?php
$result = array('error'=> 0,
	'txt' => 'Пользователь успешно зарегистрирован!',
	'wrong' => ''
);
if ($_POST['email'] !== '')
	$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!preg_match('/^[a-z0-9]{0,31}$/i',$_POST['username'],$r) || $r[0] !== $_POST['username'])
{
	$result['error'] = 1;
	$result['txt'] = 'Неверное имя пользователя!'.PHP_EOL.'Используйте только латинские буквы и цифры!'.PHP_EOL.'Без пробелов, спец-символов. Максимальная длина - 31 символ';
	$result['wrong'] = 'username';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
if ($_POST['pass'] !== $_POST['chk_pass'])
{
	$result['error'] = 1;
	$result['txt'] = 'Пароли не совпадают!'.PHP_EOL.'Введите пароль и подтверждение ещё раз!';
	$result['wrong'] = 'pass';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
	$_POST['pass'] = md5(PASS_SALT.$_POST['pass']);
$id = $engine->GetGamerID($_POST['player_name'],1);
if ($id === false)
{
	$result['error'] = 1;
	$result['txt'] = 'Игрока с псевдонимом "'.$_POST['player_name'].'" - не найдено!'.PHP_EOL.'Пожалуйста, обратитесь к администрации или посетите хотя бы 1 вечер игры!'.PHP_EOL.'Будем рады вас видеть!';
	$result['wrong'] = 'player_name';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$player_data = $engine->getGamerData(array('username'),array('username'=>$_POST['username']));
if ($player_data['username'] === $_POST['username'])
{
	$result['error'] = 1;
	$result['txt'] = 'Такое имя пользователя уже занято!'.PHP_EOL.'Пожалуйста, выберите себе другое имя.';
	$result['wrong'] = 'username';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$player_data = $engine->getGamerData(array('username'),array('id'=>$id));
if ($player_data['username'] !== '')
{
	$result['error'] = 1;
	$result['txt'] = 'Игрок с псевдонимом "'.$_POST['player_name'].'" - уже зарегистрирован!'.PHP_EOL.'Если это - Вы, пожалуйста, обратитесь к администрации для восстановления пароля!';
	$result['wrong'] = 'player_name';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$a = array('username'=>$_POST['username'], 'pass'=>$_POST['pass'], 'email'=>$_POST['email'] !== false ? $_POST['email'] : '');

$engine->rowUpdate($a,array('id'=>$id),SQL_TBLUSERS);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));