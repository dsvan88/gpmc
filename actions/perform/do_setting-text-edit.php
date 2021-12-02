<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

$settings = new Settings();

$array = [
    'type' => 'txt',
    'short_name' => trim($_POST['short_name']),
    'name' => trim($_POST['name']),
    'value' => trim($_POST['html'])
];

$settings->settingsSet($array,$_POST['id']);
$output['text'] = 'Дані збережено!';
