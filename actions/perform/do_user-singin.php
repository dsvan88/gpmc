<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';
$user = new Users();

$result = $user->login($_POST);

$output['text'] = "Вы успешно авторизованы.\r\nДобро пожаловать!";

if (!$result) {
    $output['error'] = 1;
    $output['text'] = 'Не верный логин или пароль! Проверьте их и попробуйте ещё раз!';
}
