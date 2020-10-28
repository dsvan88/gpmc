<?php
$result=array(
	'error' => 0,
	'txt' => '',
	'html' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут работать с новостями!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine->setNews($_POST);
$result['txt'] = 'Успешно!';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));