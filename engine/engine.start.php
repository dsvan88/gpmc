<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/functions.php';

[$template,$settings,$evening,$users,$images] = engineStart();

$genders=['','господин','госпожа','некто'];
$statuses = ['Гость', 'Резидент', 'Мастер'];
$userData['status'] = 'guest';

$EveningData = $evening->nearEveningGetData(['id','date','place','games','playes','playes_info']);

$settingsArray = $settings->modifySettingsArray($settings->settingsGet(array('short_name','name','value','type'),['img','txt']));

$gendersImages = [
	'none' => $settingsArray['img']['profile']['value'],
	'male' => $settingsArray['img']['male']['value'],
	'female' => $settingsArray['img']['female']['value'],
	'unknow' => $settingsArray['img']['profile']['value']
];