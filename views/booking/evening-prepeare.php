<?php

$weekId = (int) $_GET['wid'];
$dayId = (int) $_GET['did'];

$weekData = $weeks->getDataByTime();

if (!$weekData) {
	$weekData = $weeks->getDataDefault();
}

$dayData = $weekData['data'][$dayId];

$dayHtmlData = [
	'{DAY_TIME}' => $dayData['time'],
	'{DAY_INDEX}' => $dayId,
	'{DAY_GAME_MAFIA}' => '',
	'{DAY_GAME_POKER}' => '',
	'{DAY_GAME_CASH}' => ''
];

if (count($dayData['participants']) === 0 && !(isset($_SESSION['status']) && in_array($_SESSION['status'], ['admin', 'manager', 'founder']))) {
	$dayHtml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/booking/booking-none.html');
} else {
	if (isset($_SESSION['status']) && in_array($_SESSION['status'], ['admin', 'manager', 'founder'])) {
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'] . '/templates/booking/booking-edit.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'] . '/templates/participant-field-edit.html'
		];
	} else {
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'] . '/templates/booking/booking-show.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'] . '/templates/participant-field-show.html'
		];
	}
	$dayHtmlData['{DAY_INDEX}'] = $dayId;
	$dayHtmlData['{DAY_GAME_MAFIA}'] = $dayData['game'] === 'mafia' ? 'selected' : '';
	$dayHtmlData['{DAY_GAME_POKER}'] = $dayData['game'] === 'poker' ? 'selected' : '';
	$dayHtmlData['{DAY_GAME_CASH}'] = $dayData['game'] === 'cash' ? 'selected' : '';

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
