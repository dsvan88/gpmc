<?php
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
	$col = array_keys($_POST)[1];
	if ($col === 'birthday')
	{
		if ($_POST['birthday'] === '01.01.1970')
		{
			$result['error'] = 1;
			$result['txt'] = 'Вы не ввели новую дату';
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		else
			$_POST['birthday'] = strtotime($_POST['birthday']);
	}
	if ($col === 'email')
	{
		if ($_POST['email'] !== '')
		{
			$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
			if ($_POST['email'] === false)
			{
				$result['error'] = 1;
				$result['txt'] = 'Вы ввели не правильную электронную почту!';
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
		}
	}
	$engine->UpdateRow(array($col=>$_POST[$col]),array('id'=>$_SESSION['id']),MYSQL_TBLPLAYERS);

	$result['txt'] = 'Успешно изменено!';
	$result['nv'] = '<b>'.$_POST[$col].'</b>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$engine->UpdateRow(array($_POST['i']=>$_POST['html']),array('id'=>$_SESSION['id']),MYSQL_TBLPLAYERS);
	$result['error'] = 0;
	$result['txt'] = 'Успешно изменено!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}