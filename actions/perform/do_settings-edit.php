<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

$settings = new Settings();

if (isset($_POST['tg-bot']) && $_POST['tg-bot'] != ''){
    $setting = $settings->settingsGet(['id','value'],['tg-bot'])[0];
    $id = "add";
    $array=[
            'type' => 'tg-bot',
            'short_name' => 'telegram-bot_token',
            'name' => 'Токен телеграм бота',
            'value' => trim($_POST['tg-bot']),
            'by_default' => trim($_POST['tg-bot'])
        ];
    if ($setting['id'])
        $id = $setting['id'];
    if ($_POST['tg-bot'] !== $setting['value']){
        $settings->settingsSet($array,$id);
    }
    $output['text'] = 'Дані збережено!';
}
else{
    $output['error'] = 1;
    $output['text'] = 'Нічого вносити!';
}