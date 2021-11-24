<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/functions.php';

[$template,$settings,$evening,$places,$users,$images] = engineStart();

$genders=['','господин','госпожа','некто'];
$statuses = ['Гость', 'Резидент', 'Мастер'];
$userData['status'] = 'guest';

$EveningData = $evening->nearEveningGetData(['id','date','place','games','participants','participants_info']);
if (isset($EveningData['place']) && is_numeric($EveningData['place'])){
	$EveningData['place'] = $places->placeGetDataByID($EveningData['place']);
}

$settingsArray = $settings->modifySettingsArray($settings->settingsGet(array('short_name','name','value','type'),['img','txt']));

$gendersImages = [
	'none' => $settingsArray['img']['profile']['value'],
	'male' => $settingsArray['img']['male']['value'],
	'female' => $settingsArray['img']['female']['value'],
	'unknow' => $settingsArray['img']['profile']['value']
];