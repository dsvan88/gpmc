<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$user = new Users();
$result = $user->userRegistration($_POST);

if (is_array($result))
    $output = $result;
else
    $output['text'] = 'Користувач успішно зареєстрований!';