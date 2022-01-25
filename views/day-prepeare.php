<?php

$weekId = (int) $_GET['weekid'];
$dayId = (int) $_GET['dayid'];

$dayDate = str_replace(
	['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
	['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'],
	date('d.m.Y (l)', $weekData['start'] + TIMESTAMP_DAY * $dayId)
);

$dayHtmlData = [
	'{DAY_TIME}' => isset($dayData['time']) ? $dayData['time'] : '18:00',
	'{DAY_INDEX}' => $dayId,
	'{DAY_DATE}' => $dayDate,
	'{DAY_PRIM}' => isset($dayData['prim']) ? $dayData['prim'] : '',
	'{WEEK_INDEX}' => $weekId,
	'{DAY_GAME_MAFIA}' => '',
	'{DAY_GAME_POKER}' => '',
	'{DAY_GAME_BOARD}' => '',
	'{DAY_GAME_CASH}' => '',
	'{DAY_GAME_MODS_FANS}' => '',
	'{DAY_GAME_MODS_TOURNAMENT}' => ''
];

// var_dump($dayData);

if (count($dayData['participants']) === 0 && !(isset($_SESSION['status']) && in_array($_SESSION['status'], ['admin', 'manager', 'founder']))) {
	$dayHtml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/booking-none.html');
} else {
	if (isset($_SESSION['status']) && in_array($_SESSION['status'], ['admin', 'manager', 'founder'])) {
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'] . '/templates/booking-edit.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'] . '/templates/layouts/participant-field-edit.html'
		];
	} else {
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'] . '/templates/booking-show.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'] . '/templates/layouts/participant-field-show.html'
		];
	}
	$dayHtmlData['{DAY_INDEX}'] = $dayId;

	$dayHtmlData['{DAY_GAME_' . strtoupper($dayData['game']) . '}'] = 'selected';

	$dayHtmlData['{DAY_GAME_MODS_FANS}'] = in_array('fans', $dayData['mods']) ? 'checked' : '';
	$dayHtmlData['{DAY_GAME_MODS_TOURNAMENT}'] = in_array('tournament', $dayData['mods']) ? 'checked' : '';

	$dayHtmlData['{DAY_PARTICIPANTS}'] = '';
	$durations = ['', ' (на 1-2 гри)', ' (на 2-3 гри)', ' (на 3-4 гри)'];
	$playersCount = max(count($dayData['participants']), 11);
	for ($x = 0; $x < $playersCount; $x++) {
		$replace = [
			'{PARTICIPANT_INDEX}' => $x,
			'{PARTICIPANT_NUMBER}' => $x + 1,
			'{PARTICIPANT_NAME}' => '',
			'{PARTICIPANT_ARRIVE}' => '',
			'{PARTICIPANT_DURATION}' => '',
			'{PARTICIPANT_DURATION_0}' => '',
			'{PARTICIPANT_DURATION_1}' => '',
			'{PARTICIPANT_DURATION_2}' => '',
			'{PARTICIPANT_DURATION_3}' => ''
		];
		if (isset($dayData['participants'][$x]['name'])) {
			$replace['{PARTICIPANT_NAME}'] = $dayData['participants'][$x]['name'];
			$replace['{PARTICIPANT_ARRIVE}'] = $dayData['participants'][$x]['arrive'];
			$replace['{PARTICIPANT_DURATION}'] = $durations[$dayData['participants'][$x]['duration']];
			$replace['{PARTICIPANT_DURATION_' . $dayData['participants'][$x]['duration'] . '}'] = ' selected ';
		}
		$dayHtmlData['{DAY_PARTICIPANTS}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($htmlFiles['participant_row']));
	}
	$dayHtml = str_replace(array_keys($dayHtmlData), array_values($dayHtmlData), file_get_contents($htmlFiles['booking']));
}
