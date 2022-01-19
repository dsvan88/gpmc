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

if ($currentDay === -1) {
	$currentDay = 6;
}

if (isset($_GET['weekid'])) {
	$weekId = (int) $_GET['weekid'];
	if ($weekId > 0) {
		$weekData = $weeks->getDataById($weekId);
	} else {
		if ($weekId === 0 || !$weeks->checkByTime()) {
			$weekData = $weeks->getDataDefault();
		} else {
			$sunday = strtotime('next sunday') + 604800;
			$weekData = $weeks->getDataDefault($sunday);
			$weekId = -1;
		}
	}
} else {
	if (!$weeks->checkByTime()) {
		$weekData = $weeks->getDataDefault();
		$weekId = 0;
	} else {
		$weekData = $weeks->getDataByTime();
		$weekId = $weekData['id'];
	}
}

if ($weekData && isset($weekData[$currentDay]))
	$dayData = $weekData[$currentDay];
