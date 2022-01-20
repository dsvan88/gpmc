<?
$output['{WEEK_LIST}'] = '
	<h2 class="week-preview__title section__title">Тижневий розклад ігор</h2>
	<div class="week-preview__list">';

if (isset($weekData['start'])) {
	$monday = $weekData['start'];
} else {
	$monday = strtotime('last monday', strtotime('next sunday'));
	if ($weekId === -1) {
		$monday += 604800;
	}
}

$gameNames = [
	'mafia' => 'Мафия',
	'poker' => 'Покер',
	'board' => 'Настолки',
	'cash' => 'Кеш-покер'
];

$defaultDayData = $weeks->getDayDataDefault();

for ($i = 0; $i < 7; $i++) {
	if (!isset($weekData['data'][$i])) {
		$weekData['data'][$i] = $weeks->getDayDataDefault();
	} else {
		foreach ($defaultDayData as $key => $value) {
			if (!isset($weekData['data'][$i][$key])) {
				$weekData['data'][$i][$key] = $value;
			}
		}
	}
	$replace = [
		'{DAY_DATE}' => str_replace(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], ['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'], date('d.m.Y (l)', $monday + 86400 * $i)) . ' ' . $weekData['data'][$i]['time'],
		'{DAY_GAME}' => $gameNames[$weekData['data'][$i]['game']],
		'{WEEK_INDEX}' => $weekId,
		'{DAY_INDEX}' => $i,
		'{DAY_PARTICIPANTS}' => '
		<ol class="day-participants__list">
			<div class="day-participants__list-column">',
		'{DAY_ITEM_CLASS}' => ''
	];

	if ($selectedWeekIndex < $weekCurrentIndexInList) {
		$replace['{DAY_ITEM_CLASS}'] = 'day-expire';
	} elseif (
		$selectedWeekIndex === $weekCurrentIndexInList &&
		$weekId !== -1
	) {
		if ($dayCurrentId > $i)
			$replace['{DAY_ITEM_CLASS}'] = 'day-expire';

		elseif ($dayCurrentId === $i)
			$replace['{DAY_ITEM_CLASS}'] = 'day-current';

		else
			$replace['{DAY_ITEM_CLASS}'] = 'day-future';
	}

	$maxParticipantsCount = min(count($weekData['data'][$i]['participants']), 10);

	for ($x = 0; $x < $maxParticipantsCount; $x++) {
		if ($x !== 0 && $x % 5 === 0) {
			$replace['{DAY_PARTICIPANTS}'] .= '
			</div>
			<div class="day-participants__list-column">';
		}
		$replace['{DAY_PARTICIPANTS}'] .= '<li class="day-participants__list-item">' . (isset($weekData['data'][$i]['participants'][$x]) ? $weekData['data'][$i]['participants'][$x]['name'] : '') . '</li>';
	}
	$replace['{DAY_PARTICIPANTS}'] .= '</div></ol>';

	$output['{WEEK_LIST}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/layouts/item-day.html'));
}
$output['{WEEK_LIST}'] .= '
	</div>';

if ($weeksCount > 0) {
	$pagesLinks = '';
	foreach ($weeksIds as $index => $wId) {
		$pagesLinks .= "<a href='/?weekid=$wId'" . ($wId == $weeksIds[$selectedWeekIndex] ? ' class="active"' : '') . ">$wId</a>";
	}
	if ($selectedWeekIndex > 0) {
		$pagesLinks = '<a href="/?weekid=' . $weeksIds[$selectedWeekIndex - 1] . '"><i class="fa fa-angle-left"></i></a>' . $pagesLinks;
	}

	if ($selectedWeekIndex > 5) {
		$pagesLinks = '<a href="/?weekid=1"><i class="fa fa-angle-double-left"></i></a>' . $pagesLinks;
	}

	if (isset($weeksIds[$selectedWeekIndex + 1]) && $weekId !== -1) {
		$pagesLinks .= '<a href="/?weekid=' . $weeksIds[$selectedWeekIndex + 1] . '"><i class="fa fa-angle-right"></i></a>';
	}
	if ($weeksCount - 1 - $selectedWeekIndex > 5) {
		$pagesLinks .= '<a href="/?weekid=' . ($weeksCount - 1) . '"><i class="fa fa-angle-double-right"></i></a>';
	}
	$pagesLinks .= '<a href="/?weekid=-1"' . ($weekId < 1 ? ' class="active"' : '') . '><i class="fa fa-plus"></i></a>';
	$output['{WEEK_LIST}'] .= "<div class='week-preview__links'>$pagesLinks</div>";
}
