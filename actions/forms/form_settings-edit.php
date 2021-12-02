<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

$settings = new Settings();

$setting = $settings->settingsGet(['value'],['tg-bot']);

$replace = '';
if (isset($setting[0]))
    $replace = $setting[0]['value'];
$output['html'] = str_replace('{SETTINGS_TELEGRAM_BOT}',$replace,file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_settings-edit.html'));