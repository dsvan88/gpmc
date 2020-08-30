<?php
#error_log(json_encode($_POST,JSON_UNESCAPED_UNICODE));
$result=array(
	'error' => 0,
	'txt' => '',
	'nv' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут редактировать данные пользователей';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
if (!isset($_POST['html']))
{
	$col = trim($_POST['column']);
	if ($col === 'birthday')
	{
		if ($_POST['value'] === '01.01.1970')
		{
			$result['error'] = 1;
			$result['txt'] = 'Вы не ввели новую дату';
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		else
			$_POST['value'] = strtotime($_POST['value']);
	}
	if ($col === 'email')
	{
		if ($_POST['value'] !== '')
		{
			$_POST['value'] = filter_var($_POST['value'], FILTER_VALIDATE_EMAIL);
			if ($_POST['value'] === false)
			{
				$result['error'] = 1;
				$result['txt'] = 'Вы ввели не правильную электронную почту!';
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
		}
	}

	$engine->UpdateRow(array($col=>$_POST['value']),array('id'=>$_SESSION['id']),MYSQL_TBLGAMERS);

	$result['txt'] = 'Успешно изменено!';
	$result['nv'] = '<b>'.$_POST['value'].'</b>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$engine->UpdateRow(array($_POST['column']=>$_POST['html']),array('id'=>$_SESSION['id']),MYSQL_TBLGAMERS);
	$result['error'] = 0;
	$result['txt'] = 'Успешно изменено!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}