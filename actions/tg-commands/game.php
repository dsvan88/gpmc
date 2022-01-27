<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegramId = $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name', 'telegram', 'status'], ['telegramid' => $telegramId]);

if (!isset($userData['id'])) {
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш псевдоним (кириллицей)";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';

    $weeks = new Weeks;

    $requestData = [
        'method' => '+',
        'arrive' => '',
        'date' => '',
        'duration' => '',
        'dayNum' => -1,
        'userId' => $userData['id'],
        'userName' => $userData['name'],
        'userStatus' => $userData['status']
    ];
    foreach ($matches[0] as $value) {

        $requestData['currentDay'] = getdate()['wday'] - 1;

        if ($requestData['currentDay'] === -1)
            $requestData['currentDay'] = 6;

        if (preg_match('/^(\+|-)/', $value)) {

            $requestData['method'] = $value[0];
            $withoutMethod = trim(mb_substr($value, 1, 6, 'UTF-8'));
            $dayName = mb_strtolower(mb_substr($withoutMethod, 0, 3, 'UTF-8'));

            if (in_array($dayName, ['сг', 'сег'], true)) {
                $requestData['dayNum'] = $requestData['currentDay'];
            } elseif ($dayName === 'зав') {
                $requestData['dayNum'] = $requestData['currentDay'] + 1;
                if ($requestData['dayNum'] === 7)
                    $requestData['dayNum'] = 0;
            } else {
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
            }
        } elseif (strpos($value, ':') !== false) {
            $requestData['arrive'] = $value;
        } elseif (strpos($value, '.') !== false) {
            $requestData['date'] = $value;
        } elseif (strpos($value, '-') !== false) {
            $requestData['duration'] = substr($value, 0, 1);
        }
    }

    if ($requestData['method'] === '-') {
        $output['message'] = $weeks->dayUserUnregistrationByTelegram($requestData);
    } else {
        $output['message'] = $weeks->dayUserRegistrationByTelegram($requestData);
    }
}
