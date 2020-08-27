<?
$result=array(
	'error' => 0,
	'txt' => ''
);
$id = $engine->GetGamerID($_POST['old_name'],1);
if ($id === false)
{
	$result['error'] = 1;
	$result['txt'] = 'Не найден соотвтествующий веременный пользователь!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
elseif ($engine->GetGamerID($_POST['new_name'],1) !== false)
{
	$result['error'] = 1;
	$result['txt'] = 'Пользователь с таким именем уже зарегистрирован!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine->UpdateRow(array('name'=>$_POST['new_name']),array('id'=>$id),MYSQL_TBLGAMERS);
$result['txt'] = 'Успешно!';
$result['nn'] = $_POST['new_name'];
exit(json_encode($result,JSON_UNESCAPED_UNICODE));