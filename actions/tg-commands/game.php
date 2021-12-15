<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$users = new Users;

$telegramId = $_POST['message']['from']['username'] === '' ? $_POST['message']['from']['id'] : $_POST['message']['from']['username'];

$userData = $users->usersGetData(['id','name'],[ 'telegram' => $telegramId ]);

if (!isset($userData['id'])){
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
    $users->usersSaveUnknowTelegram($telegramId);
}
elseif ($userData['name'] === 'tmp_telegram_user'){
    $output['message'] = "Извините! Не узнаю вас в гриме :(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
}
else{
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

    $evenings = new Evenings;

    $buffer = [ '+' ];
    for ($x=0; $x < count($args); $x++) {
        $args[$x] = trim($args[$x]);
        if ($args[$x] === '-'){
            $buffer[0] = '-';
        }
        else {
            $buffer[1] = $args[$x];
        }
    }
    if ($buffer[0] === '-'){
        $output['message'] = $evenings->eveningsParticipantBookedByTelegram($command,$userData['id']);
    }
    else{
        $userData['arrive'] = '';
        if (isset($buffer[1]))
            $userData['arrive'] = $buffer[1];
        $userData['duration'] = 0;
        $output['message'] = $evenings->eveningsParticipantBookedByTelegram($command,$userData);
    }
}