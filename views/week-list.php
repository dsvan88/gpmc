<?
$output['{WEEK_LIST}'] = '
	<h2 class="week-preview__title section__title">Тижневий розклад ігор</h2>
	<div class="week-preview__list">';

$weekData = $weeks->getDataByTime();

if (!$weekData) {
	$weekData = $weeks->getDataDefault();
}

$monday = strtotime('last monday', strtotime('next sunday'));

$gameNames = [
	'mafia' => 'Мафия',
	'poker' => 'Покер',
	'cash' => 'Кеш-покер'
];

for ($i = 0; $i < 7; $i++) {
	if (!isset($weekData['data'][$i])) {
		$weekData['data'][$i] = $weeks->getDayDataDefault();
	}
	$replace['{DAY_DATE}'] = str_replace(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], ['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'], date('d.m.Y (l)', $monday + 86400 * $i)) . ' ' . $weekData['data'][$i]['time'];
	$replace['{DAY_GAME}'] = $gameNames[$weekData['data'][$i]['game']];
	$replace['{WEEK_INDEX}'] = $weekData['id'];
	$replace['{DAY_INDEX}'] = $i;

	$replace['{DAY_PARTICIPANTS}'] = '<ol class="day-participants__list">';
	for ($x = 0; $x < 4; $x++) {
		$replace['{DAY_PARTICIPANTS}'] .= '<li class="day-participants__list-item">' . (isset($weekData['data'][$i]['participants'][$x]) ? $weekData['data'][$i]['participants'][$x]['name'] : '') . '</li>';
	}
	$replace['{DAY_PARTICIPANTS}'] .= '</ol>';

	$output['{WEEK_LIST}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/layouts/item-day.html'));
}
$output['{WEEK_LIST}'] .= '
	</div>';
