<?php
$result=array(
	'error' => 0,
	'txt' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут оставлять комментарии';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine->InsertRow(array('author'=>$_SESSION['id'],'type'=>$_POST['type'],'target'=>$_POST['id'],'txt'=>$_POST['html']),MYSQL_TBLCOMM);
$result['error'] = 0;
$result['txt'] = 'Успешно добавлено!';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));