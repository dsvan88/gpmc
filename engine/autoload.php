<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/functions.php';

[$template, $settings, $weeks, $places, $users, $images, $news] = engineStart();

$genders = ['', 'господин', 'госпожа', 'некто'];
$statuses = ['Гость', 'Резидент', 'Мастер'];
$userData['status'] = 'guest';

$settingsArray = $settings->modifySettingsArray($settings->settingsGet(['short_name', 'name', 'value', 'type'], ['img', 'txt']));

$gendersImages = [
	'none' => $settingsArray['img']['profile']['value'],
	'male' => $settingsArray['img']['male']['value'],
	'female' => $settingsArray['img']['female']['value'],
	'unknow' => $settingsArray['img']['profile']['value']
];

$currentDay = getdate()['wday'] - 1;

if ($currentDay === -1)
	$currentDay = 6;

$weekData = $weeks->getDataByTime();

if ($weekData)
	$dayData = $weekData[$currentDay];
