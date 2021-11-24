<?php
$output['{SCRIPTS}'] .= '
    <script defer type="text/javascript" src="js/jquery.cleditor.min.js"></script>
';
$htmlFiles=[
	'welcome'=>$_SERVER['DOCUMENT_ROOT'].'/templates/welcome.html',
	'booking'=>$_SERVER['DOCUMENT_ROOT'].'/templates/booking-show.html',
	'participant_row'=>$_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-show.html',
];
$output['{MAIN_CONTENT}'] = file_get_contents($htmlFiles['welcome']);

if (!isset($EveningData['participants_info']) && !(isset($_SESSION['status']) && in_array($_SESSION['status'],['admin','manager','founder','master']))){
	$htmlFiles['booking'] = $_SERVER['DOCUMENT_ROOT'].'/templates/booking-none.html';
	$output['{MAIN_CONTENT}'] = file_get_contents($htmlFiles['welcome']);
	$output['{ABOUT_GAME}'] =  $settingsArray['txt']['about-game']['value'];
	$output['{NEAR_EVENING_BLOCK}'] = file_get_contents($htmlFiles['booking']);
	$output['{NEWS_BLOCK}'] = '';
}
else{
	if (isset($_SESSION['status']) && in_array($_SESSION['status'],['admin','manager','founder','master'])){
		$htmlFiles['booking'] = $_SERVER['DOCUMENT_ROOT'].'/templates/booking-edit.html';
		$htmlFiles['participant_row'] =$_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-edit.html';
	}
	$output['{MAIN_CONTENT}'] = file_get_contents($htmlFiles['welcome']);
	$output['{ABOUT_GAME}'] =  $settingsArray['txt']['about-game']['value'];
	$output['{NEAR_EVENING_BLOCK}'] = file_get_contents($htmlFiles['booking']);
	$output['{NEWS_BLOCK}'] = '';

	$output['{EVENING_PLACE}'] = '';
	$output['{EVENING_PLACE_INFO}'] = '';

	if (isset($EveningData['place'])){
		$output['{EVENING_DATE}'] = date('d.m.Y H:i',$EveningData['date']);
		$output['{EVENING_PLACE}'] = $EveningData['place']['name'];
		$output['{EVENING_PLACE_INFO}'] = $EveningData['place']['info'];
	}
	else{
		$output['{EVENING_DATE}'] = date('d.m.Y H:i');
	}
	$output['{EVENING_PARTICIPANTS}'] = '';
	if (isset($EveningData['participants_info']) && $EveningData['participants_info'] != ''){
		$EveningData['participants_info'] = json_decode($EveningData['participants_info'],true);
		$playersCount = max(count($EveningData['participants_info']), 11);
		$durations = ['',' (на 1-2 гри)',' (на 2-3 гри)',' (на 3-4 гри)'];
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
			$output['{EVENING_PARTICIPANTS}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($htmlFiles['participant_row']));
		}
	}
}
