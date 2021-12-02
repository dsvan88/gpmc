<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
$user = new Users();

$result = $user->login($_POST);

if ($result !== true){
    $output['error'] = 1;
    $output['text'] = $result;
}
else
    $output['text'] = 'Користувач успішно зареєстрований!';