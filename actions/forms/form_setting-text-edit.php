<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

$settings = new Settings();

$setting = $settings->settingsGet(['id','short_name','name','value'],['txt'],['short_name'=>$_POST['settingName']]);

$replace = [];
if (isset($setting[0])){
    $replace['{SETTING_INDEX}'] = $setting[0]['id'];
    $replace['{SETTING_SHORTNAME}'] = $setting[0]['short_name'];
    $replace['{SETTING_NAME}'] = $setting[0]['name'];
    $replace['{SETTING_VALUE}'] = $setting[0]['value'];
}
else{
    $replace['{SETTING_INDEX}'] = 'add';
    $replace['{SETTING_SHORTNAME}'] = $_POST['settingName'];
    $replace['{SETTING_NAME}'] = '';
    $replace['{SETTING_VALUE}'] = '';
}

$output['html'] = str_replace(array_keys($replace),array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_setting-text-edit.html'));