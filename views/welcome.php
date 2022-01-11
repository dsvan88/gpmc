<?php
$output['{SCRIPTS}'] .= '';
$output['{MAIN_CONTENT}'] = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/welcome.html');

$output['{ABOUT_GAME}'] =  $settingsArray['txt']['about-game']['value'];

if (isset($_SESSION['id']) && $_SESSION['status'] === 'admin' && $users->checkToken()) {
	$output['{ABOUT_GAME}'] = '
		<div class="setting-text-dashboard">
			<i class="fa fa-pencil-square-o setting-text-dashboard__button" data-action="setting-text-edit-form" data-setting-name="about-game"></i>
		</div>
		' . $output['{ABOUT_GAME}'];
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/views/week-list.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/news-preview.php';
