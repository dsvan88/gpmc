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

$weekId = 0;
if (isset($_GET['weekid'])) {
	$weekId = (int) $_GET['weekid'];
}

[$weekCurrentId, $weeksIds, $weekCurrentIndexInList, $weekData] = $weeks->autoloadWeekData($weekId);

$weeksCount = count($weeksIds);

if ($weekId > 0) {
	$selectedWeekIndex = array_search($weekId, $weeksIds);
} else {
	$selectedWeekIndex = $weekCurrentIndexInList;
}

$dayCurrentId = getdate()['wday'] - 1;

if ($dayCurrentId === -1) {
	$dayCurrentId = 6;
}

if ($weekData && isset($weekData[$dayCurrentId]['game'])) {
	$dayData = $weekData[$dayCurrentId];
} else {
	$dayData = $weeks->getDayDataDefault();
}
