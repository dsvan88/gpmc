<?
$result=array(
	'error' => 0,
	'txt' => ''
);
$_POST['uid'] = (int) $_POST['uid'];
$userName = $engine->getGamerName($_POST['uid']);
if (strpos($userName,'tmp_user') !== false)
{
	$result['error'] = 1;
	$result['txt'] = 'Обраний користувач, вже має ім’я: "'.$userName.'"! Обновіть сторінку!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
elseif ($engine->GetGamerID($_POST['new_name'],1) !== false)
{
	$result['error'] = 1;
	$result['txt'] = "Користувач з таким псевдонімом вже існує в системі!\r\nОберіть, будь-ласка іншій псевдонім";
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine->rowUpdate(array('name'=>$_POST['new_name']),array('id'=>$_POST['uid']),SQL_TBLUSERS);
$result['txt'] = 'Вдало виконано!';
$result['newName'] = $_POST['new_name'];
$result['uid'] = $_POST['uid'];
exit(json_encode($result,JSON_UNESCAPED_UNICODE));