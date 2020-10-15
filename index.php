<?php 
$root_path = $_SERVER['DOCUMENT_ROOT'];
$engine_set = 'GETS';
require $root_path.'/engine/engine.php';
$engine = new GetDatas();
$settings = $engine->ModifySettingsArray($engine->GetSettings(array('shname','name','value','type'),['img','txt']));
if (!isset($_SESSION['ba']) || $_SESSION['ba'] < 1)
{
	$EveningData = $engine->GetNearEveningData(['id','date','place','games','gamers','gamers_info']);
	if ($EveningData === false)
	{
		$EveningData['ready'] = false;
		$EveningData['start'] = false;
	}
	else 
		$EveningData['start'] = $EveningData['date']-$_SERVER['REQUEST_TIME'] < TIME_MARGE ? true : false;
	$img_genders=array($settings['img']['profile']['value'],$settings['img']['male']['value'],$settings['img']['female']['value'],$settings['img']['profile']['value']);
}
$genders=array('','господин','госпожа','некто');
$userData['status'] = 'guest';
$user_statuses = ['user','resident','resident'];
if (isset($_SESSION['status']))
{
	$userData = $engine->GetGamerData(array('name','fio','rank','ar'), array('id'=>$_SESSION['id']));
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