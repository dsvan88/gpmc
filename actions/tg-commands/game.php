<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegram = isset($_POST['message']['from']['username']) ? $_POST['message']['from']['username'] : '';

$telegramId = $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name', 'telegram'], ['telegramid' => $telegramId]);

if (!isset($userData['id'])) {
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
    $users->usersSaveUnknowTelegram(['telegram' => $telegram, 'telegramid' => $telegramId]);
} elseif ($userData['name'] === 'tmp_telegram_user') {
    $output['message'] = "Извините! Не узнаю вас в гриме :(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';

    $weeks = new Weeks;

    $requestData = [
        'method' => '+',
        'time' => '',
        'date' => '',
        'dayNum' => -1
    ];
    foreach ($matches[0] as $value) {
        if (preg_match('/^(\+|-)/', $value)) {
            $requestData['method'] = $value[0];
            $dayName = mb_strtolower(mb_substr($value, 1, 3, 'UTF-8'));

            $daysArray = [
                ['пн', 'пон'],
                ['вт', 'вто'],
                ['ср', 'сре'],
                ['чт', 'чет'],
                ['пт', 'пят'],
                ['сб', 'суб'],
                ['вс', 'вос']
            ];

            foreach ($daysArray as $num => $daysNames) {
                if (in_array($dayName, $daysNames, true)) {
                    $requestData['dayNum'] = $num;
                    break;
                }
            }
        } elseif (strpos($value, ':') !== false) {
            $requestData['time'] = $value;
        } elseif (strpos($value, '.') !== false) {
            $requestData['date'] = $value;
        }
    }

    $currentDay = getdate()['wday']--;

    if ($currentDay === -1)
        $currentDay = 6;

    if ($currentDay > $requestData['dayNum']) {
        $output['message'] = 'Не могу записать Вас на уже прошедший день! Sowwy:(';
    } else {
        if ($requestData['method'] === '-') {
            // $output['message'] = $weeks->dayUserUnregistrationByTelegram($requestData);
        } else {
            $userData['arrive'] = '';
            if ($requestData['time'] !== '')
                $userData['arrive'] = $requestData['time'];
            $userData['duration'] = 0;
            // $output['message'] = $weeks->dayUserRegistrationByTelegram($requestData);
        }
    }
}
