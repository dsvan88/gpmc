<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/engine.start.php';

$settingsArray = $settings->modifySettingsArray($settings->settingsGet(array('short_name','name','value','type'),['img','txt']));

$output = [
	'{STYLE}' => '
		<link rel="stylesheet" href="./css/style.css?v='.$_SERVER['REQUEST_TIME'].'" />
		<link rel="stylesheet" href="./css/modified-style.css?v='.$_SERVER['REQUEST_TIME'].'" />
		',
	'{SCRIPTS}' => '',
	'{HEADER_CONTENT}' => file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/header-content.html'),
	'{WEBSITE_TITLE}' => 'Mafia Game v'.SCRIPT_VERSION,
	'{MAIN_CONTENT}' => ''
];

if (isset($_SESSION['id']))
{
	$userData = $users->getUsersData(['name','fio','rank','admin'], ['id'=>$_SESSION['id']]);
	$userData['status'] = $_SESSION['status'];
	$output['{PROFILE_BUTTON}'] = '<a class="header__profile-button" data-action="profile-from"><i class="fa fa-user-secret" aria-hidden="true"></i></a>';
}
else {
	$output['{PROFILE_BUTTON}'] = '<a class="header__profile-button" data-action="login-from">Вход</a>';
}

$output['{HEADER_LOGO}'] = "<a href='http://$_SERVER[SERVER_NAME]/'>".$images->inputImage($settingsArray['img']['MainLogo']['value'],['title'=>$settingsArray['img']['MainLogo']['name']]).'</a>';

require $_SERVER['DOCUMENT_ROOT'].'/views/welcome.php';

echo str_replace(array_keys($output),array_values($output),$template);