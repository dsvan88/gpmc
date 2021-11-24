<?php
$output['{SCRIPTS}'] .= '
    <script defer type="text/javascript" src="js/jquery.cleditor.min.js"></script>
';
$htmlFiles=[
	'welcome'=>$_SERVER['DOCUMENT_ROOT'].'/templates/welcome.html',
	'booking'=>$_SERVER['DOCUMENT_ROOT'].'/templates/booking/booking-show.html',
	'participant_row'=>$_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-show.html',
];
$output['{MAIN_CONTENT}'] = file_get_contents($htmlFiles['welcome']);
$output['{ABOUT_GAME}'] =  $settingsArray['txt']['about-game']['value'];
$output['{NEWS_BLOCK}'] = '';

require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/booking.php';
