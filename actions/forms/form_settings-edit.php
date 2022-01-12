<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.settings.php';

$settings = new Settings();

$setting = $settings->settingsGet(['type', 'value', 'name'], ['tg-bot', 'tg-pinned']);

$replace = '';

$i = -1;
while (isset($setting[++$i])) {
    $replace .= "
    <div class='common-form__row'>
		<input class='common-form__input' type='text' name='{$setting[$i]['type']}' placeholder='{$setting[$i]['name']}' value='{$setting[$i]['value']} ' />
	</div>";
}

$output['html'] = str_replace('{SETTINGS_ROWS}', $replace, file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/forms/form_settings-edit.html'));
