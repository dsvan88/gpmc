<?php
$output['{SCRIPTS}'] .= '
    <script defer type="text/javascript" src="js/jquery.cleditor.min.js"></script>
';
$output['{MAIN_CONTENT}'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/welcome.html');
$output['{ABOUT_GAME}'] =  $settingsArray['txt']['about-game']['value'];
$output['{NEAR_EVENING_BLOCK}'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/booking.html');
$output['{NEWS_BLOCK}'] = '';


$output['{EVENING_DATE}'] = date('d.m.Y H:i',$EveningData['date']);
$output['{EVENING_PLACE}'] = $EveningData['place']['name'];
$output['{EVENING_PLACE_INFO}'] = $EveningData['place']['info'];
$output['{EVENING_PARTICIPANTS}'] = '';
	// $i=-1;
	// $max = $EveningData['gamers'] !== '' ? count($EveningData['gamers']) : 11;
	// while(++$i < $max)
	// 	include $root_path.'/templates/gamer-field.php';
