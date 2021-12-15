<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$users = new Users;

$userData = $users->usersGetData(['id','name'],[ 'telegram' => $_POST['message']['from']['username'] === '' ? $_POST['message']['from']['id'] : $_POST['message']['from']['username'] ]);

if (!isset($userData['id'])){
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил!";
}
else{
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

    $evenings = new Evenings;

    $buffer = [ '+' ];
    for ($x=0; $x < count($args); $x++) { 
        if ($args[$x] === '-')
            $buffer[0] = '-';
        else $buffer[1] = $args[$x];
    }
    if ($buffer[0] === '-'){
        $output['message'] = "Игрок $userData[name] успешно отписался с ближайшего вечер игры в $command!:(";
    }
    else{
        $userData['arrive'] = '';
        if (isset($buffer[1]))
            $userData['arrive'] = $buffer[1];
        $userData['duration'] = 0;
        $output['message'] = $evenings->eveningsParticipantBookedByTelegram($command,$userData);
    }
}