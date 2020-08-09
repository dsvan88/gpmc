<?php 
$root_path = $_SERVER['DOCUMENT_ROOT'];
$engine_set = 'GETS';
require $root_path.'/engine/engine.php';
$engine = new GetDatas();
$settings = $engine->ModifySettingsArray($engine->GetSettings(array('shname','name','value','type'),['img','txt']));
if (!isset($_SESSION['ba']) || $_SESSION['ba'] < 1)
{
	$EveningData = $engine->GetNearEveningData(array('id','date','place','games','players','times','tobe'));
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
?>
<!DOCTYPE html>
<html>
<?
require $root_path.'/main/head.php';
require $root_path.'/main/body.php';
?>
</html>