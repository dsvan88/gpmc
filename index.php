<?php
require $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
require $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';
$action = new Action();

$settings = new Settings($action);

$settingsArray = $settings->modifySettingsArray($settings->settingsGet(array('shname','name','value','type'),['img','txt']));
if (!isset($_SESSION['admin']) || $_SESSION['admin'] < 1)
{
	$EveningData = $engine->nearEveningGetData(['id','date','place','games','playes','playes_info']);
	if ($EveningData === false)
	{
		$EveningData['ready'] = false;
		$EveningData['start'] = false;
	}
	else 
		$EveningData['start'] = $EveningData['date']-$_SERVER['REQUEST_TIME'] < TIME_MARGE ? true : false;
	$img_genders=array($settingsArray['img']['profile']['value'],$settingsArray['img']['male']['value'],$settingsArray['img']['female']['value'],$settingsArray['img']['profile']['value']);
}

$genders=['','господин','госпожа','некто'];
$userData['status'] = 'guest';
$statuses = ['Гость', 'Резидент', 'Основатель'];
$user_statuses = ['user','resident','resident'];
if (isset($_SESSION['status']))
{
	$userData = $engine->getGamerData(array('name','fio','rank','ar'), array('id'=>$_SESSION['id']));
	$userData['status'] = $user_statuses[$_SESSION['status']];
}
	

?>
<!DOCTYPE html>
<html>
<?
	require $root_path.'/main/head.php';
	require $root_path.'/main/body.php';
?>
</html>