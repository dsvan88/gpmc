<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/engine.start.php';

if ( isset($_SESSION['id']) && ( $_SESSION['expire'] < $_SERVER['REQUEST_TIME'] || !$users->checkToken() )){
    $users->logout();
}

$output = [
	'{STYLE}' => '
		<link rel="stylesheet" href="./css/style.css?v='.$_SERVER['REQUEST_TIME'].'" />
		<link rel="stylesheet" href="./css/modified-style.css?v='.$_SERVER['REQUEST_TIME'].'" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.structure.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.cleditor.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.min.css"/>
		',
	'{SCRIPTS}' => '
		<script defer type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	    <script defer type="text/javascript" src="js/jquery-ui.min.js"></script>
    	<script defer type="text/javascript" src="js/jquery.datetimepicker.full.min.js"></script>
	    <script defer type="text/javascript" src="js/get_script.php/?js=modals"></script>
	    <script defer type="text/javascript" src="js/get_script.php/?js=main_func"></script>
    	<script defer type="text/javascript" src="js/get_script.php/?js=main"></script>
	',
	'{HEADER_CONTENT}' => file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/header-content.html'),
	'{WEBSITE_TITLE}' => 'Mafia Game v'.SCRIPT_VERSION,
	'{MAIN_CONTENT}' => ''
];

if (isset($_SESSION['id']))
	$output['{PROFILE_BUTTON}'] = '<a class="header__profile-button" data-action="user-logout"><i class="fa fa-user-secret"></i></a>';
else
	$output['{PROFILE_BUTTON}'] = '<a class="header__profile-button" data-action="user-singin-form">Вход</a>';

$output['{HEADER_LOGO}'] = "<a href='http://$_SERVER[SERVER_NAME]/'>".$images->inputImage($settingsArray['img']['MainLogo']['value'],['title'=>$settingsArray['img']['MainLogo']['name']]).'</a>';

require $_SERVER['DOCUMENT_ROOT'].'/views/welcome.php';

echo str_replace(array_keys($output),array_values($output),$template);