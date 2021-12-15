<?php
$eveningHtmlData = [
	'{EVENING_DATE}' => date('d.m.Y H:i',$_SERVER['REQUEST_TIME']),
	'{EVENING_PLACE}' => '',
	'{EVENING_PLACE_INFO}' => '',
	'{EVENING_INDEX}' => 0,
	'{EVENING_GAME_MAFIA}' => '',
	'{EVENING_GAME_POKER}' => '',
	'{EVENING_GAME_CASH}' => ''

];

if (!isset($EveningData['participants_info']) && !(isset($_SESSION['status']) && in_array($_SESSION['status'],['admin','manager','founder']))){
	$eveningHtml = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/templates/booking/booking-none.html');
}
else{
	if (isset($_SESSION['status']) && in_array($_SESSION['status'],['admin','manager','founder'])){
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'].'/templates/booking/booking-edit.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-edit.html'
		];
	}
    else{
		$htmlFiles = [
			'booking' => $_SERVER['DOCUMENT_ROOT'].'/templates/booking/booking-show.html',
			'participant_row' => $_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-show.html'
		];
	}

	if (isset($EveningData['place'])){
		$eveningHtmlData['{EVENING_DATE}'] = date('d.m.Y H:i',$EveningData['date']);
		$eveningHtmlData['{EVENING_PLACE}'] = $EveningData['place']['name'];
		$eveningHtmlData['{EVENING_PLACE_INFO}'] = $EveningData['place']['info'];
		$eveningHtmlData['{EVENING_INDEX}'] = $EveningData['id'];
		$eveningHtmlData['{EVENING_GAME_MAFIA}'] = $EveningData['game'] === 'mafia' ? 'selected' : '';
		$eveningHtmlData['{EVENING_GAME_POKER}'] = $EveningData['game'] === 'poker' ? 'selected' : '';
		$eveningHtmlData['{EVENING_GAME_CASH}'] = $EveningData['game'] === 'cash' ? 'selected' : '';
	}

	$eveningHtmlData['{EVENING_PARTICIPANTS}'] = '';

	if (isset($EveningData['participants_info']) && $EveningData['participants_info'] != ''){
		$playersCount = max(count($EveningData['participants_info']), 11);
		$durations = [ '',' (на 1-2 гри)',' (на 2-3 гри)',' (на 3-4 гри)' ];
		for ($x=0; $x < $playersCount; $x++) {
			$replace=[
				'{PARTICIPANT_INDEX}' => $x,
				'{PARTICIPANT_NUMBER}' => $x+1,
				'{PARTICIPANT_NAME}' => '',
				'{PARTICIPANT_ARRIVE}' => '',
				'{PARTICIPANT_DURATION}' => '',
				'{PARTICIPANT_DURATION_0}' => '',
				'{PARTICIPANT_DURATION_1}' => '',
				'{PARTICIPANT_DURATION_2}' => '',
				'{PARTICIPANT_DURATION_3}' => ''
			];
			if (isset($EveningData['participants_info'][$x]['name'])){
				$replace['{PARTICIPANT_NAME}'] = $EveningData['participants_info'][$x]['name'];
				$replace['{PARTICIPANT_ARRIVE}'] = $EveningData['participants_info'][$x]['arrive'];
				$replace['{PARTICIPANT_DURATION}'] = $durations[$EveningData['participants_info'][$x]['duration']];
				$replace['{PARTICIPANT_DURATION_'.$EveningData['participants_info'][$x]['duration'].'}'] = ' selected ';
			}
			$eveningHtmlData['{EVENING_PARTICIPANTS}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($htmlFiles['participant_row']));
		}
	}
	$eveningHtml = str_replace(array_keys($eveningHtmlData), array_values($eveningHtmlData), file_get_contents($htmlFiles['booking']));
}