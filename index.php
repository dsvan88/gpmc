<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/autoload.php';

if (isset($_SESSION['id']) && ($_SESSION['expire'] < $_SERVER['REQUEST_TIME'] || !$users->checkToken())) {
	$users->logout();
}

$output = [
	'{STYLE}' => '
		<link rel="stylesheet" href="./css/style.css?v=' . $_SERVER['REQUEST_TIME'] . '" />
		<link rel="stylesheet" href="./css/modified-style.css?v=' . $_SERVER['REQUEST_TIME'] . '" />
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
	'{HEADER_CONTENT}' => file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/header-content.html'),
	'{HEADER_ADD_MENU_ITEMS}' => '',
	'{WEBSITE_TITLE}' => 'Mafia Game v' . SCRIPT_VERSION,
	'{WEBSITE_DESCRIPTION}' => 'Kriviy Rih Mafia Club основан людьми, для людей. Приходите к нам поиграть в класическую, салоную игру Мафия!',
	'{MAIN_CONTENT}' => '',
	'{FOOTER_CONTENT}' => isset($settingsArray['txt']['footer-text']['value']) ? $settingsArray['txt']['footer-text']['value'] : 'Нет значения',
	'{CLUB_NAME}' => MAFCLUB_NAME,

];

if (isset($_SESSION['id'])) {
	if ($_SESSION['avatar'] == '') {
		$profileImage = $_SESSION['gender'] === '' ? $settingsArray['img']['profile']['value'] : $settingsArray['img'][$_SESSION['gender']]['value'];
	} else {
		$profileImage = FILE_USRGALL . $_SESSION['id'] . '/' . $_SESSION['avatar'];
	}
	$profileImage = $images->inputImage($profileImage, ['title' => $_SESSION['name']]);
	$output['{PROFILE_BUTTON}'] = "
	<a class='header__profile-button' data-action='user-profile-form'>
		$profileImage
	</a>
	<div class='header__profile-options'>
		{PROFILE_MENU}
	</div>";
	$output['{PROFILE_MENU}'] = file_get_contents("$_SERVER[DOCUMENT_ROOT]/templates/layouts/header-profile-menu-$_SESSION[status].html");
	/* <li class='header__profile-menu-item'>
			<a href='/?page=near-evening'>Налаштувати вечір</a>
			<div class='header__profile-menu-bar'></div>
		</li> */
	if (isset($_SESSION['id']) && in_array($_SESSION['status'], ['admin', 'manager']) && $users->checkToken()) {
		$output['{SCRIPTS}'] .= '<script defer type="text/javascript" src="js/get_script.php/?js=admin-func"></script>';
	}
} else
	$output['{PROFILE_BUTTON}'] = '<a class="header__profile-button" data-action="user-singin-form">Вхід</a>';

$output['{HEADER_LOGO}'] = "<a href='$_SERVER[HTTP_X_FORWARDED_PROTO]://$_SERVER[SERVER_NAME]/'>" . $images->inputImage($settingsArray['img']['MainLogo']['value'], ['title' => $settingsArray['img']['MainLogo']['name']]) . '</a>';

if (isset($_GET['gid'])) {
	require "$_SERVER[DOCUMENT_ROOT]/views/game.php";
}
if (isset($_GET['weekid']) && isset($_GET['dayid'])) {
	require "$_SERVER[DOCUMENT_ROOT]/views/day-edit.php";
} elseif (isset($_GET['news'])) {
	require "$_SERVER[DOCUMENT_ROOT]/views/news.php";
} else {
	if (!isset($_GET['page'])) {
		$_GET['page'] = 'welcome';
	}
	require "$_SERVER[DOCUMENT_ROOT]/views/$_GET[page].php";
}

if (isset($_SESSION['id']) && $_SESSION['status'] === 'admin' && $users->checkToken()) {
	$output['{FOOTER_CONTENT}'] = '
		<div class="setting-text-dashboard">
			<i class="fa fa-pencil-square-o setting-text-dashboard__button" data-action="setting-text-edit-form" data-setting-name="footer-text"></i>
		</div>
		' . $output['{FOOTER_CONTENT}'];
}

$outputHtml = str_replace(array_keys($output), array_values($output), $template);

print $outputHtml;
