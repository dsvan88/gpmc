<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$users = new Users;

$userData = $users->usersGetData(['id','name'],['telegram' => $_POST['message']['from']['username']]);

if (!isset($userData['id'])){
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил!";
}
else{
    if ($args[0] === '+')
        $output['message'] = "Игрок $userData[name] успешно зарегистрирован на ближайший вечер игры в $command! Планирует быть на $args[1]";
    else
        $output['message'] = "Игрок $userData[name] успешно отписался с ближайшего вечер игры в $command!:(";
}