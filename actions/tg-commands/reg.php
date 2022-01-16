<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegram = isset($_POST['message']['from']['username']) ? $_POST['message']['from']['username'] : '';

$telegramId = $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name', 'telegram', 'status'], ['telegramid' => $telegramId]);

if (!isset($userData['id'])) {
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш псевдоним (кириллицей)";
} elseif ($userData['name'] === 'tmp_telegram_user') {
    $output['message'] = "Извините! Не узнаю вас в гриме :(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш псевдоним (кириллицей)";
} elseif (!in_array($userData['status'], ['manager', 'admin'], true)) {
    $output['message'] = "Команда не знайдена";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';

    $weeks = new Weeks;

    $userRegData = $users->usersGetData(['id', 'name'], ['id' => $users->userGetId($name)]);

    $requestData = [
        'method' => '+',
        'arrive' => '',
        'date' => '',
        'duration' => '',
        'dayNum' => -1,
        'userId' => 0,
        // 'userName' => $userRegData['name'],
        'userStatus' => $userData['status']
    ];

    $output['message'] = '';
    foreach ($args as $value) {

        $value = trim($value);

        $currentDay = getdate()['wday'] - 1;

        if ($currentDay === -1)
            $currentDay = 6;

        if (preg_match('/^(\+|-)/', $value)) {

            $requestData['method'] = $value[0];
            $withoutMethod = trim(mb_substr($value, 1, 6, 'UTF-8'));
            $dayName = mb_strtolower(mb_substr($withoutMethod, 0, 3, 'UTF-8'));

            if (in_array($dayName, ['сг', 'сег'], true)) {
                $requestData['dayNum'] = $currentDay;
            } elseif ($dayName === 'зав') {
                $requestData['dayNum'] = $currentDay + 1;
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
        } elseif ($requestData['userId'] < 2) {
            $output['message'] .= "$value\r\n";
            $userRegData = $users->usersGetData(['id', 'name'], ['id' => $users->userGetId($value)]);
            if (!$userRegData) {
                $requestData['userId'] = $userRegData['id'];
                $requestData['userName'] = $userRegData['name'];
            }
        }
    }

    if ($requestData['userId'] < 2) {
        $output['message'] .= 'Я не нашёл такого пользователя:( ' . json_encode($requestData, true) . ' ' . json_encode($args, true);
    } else {
        if ($currentDay > $requestData['dayNum']) {
            $output['message'] = 'Не могу записать Вас на уже прошедший день! Sowwy:(';
        } else {
            if ($requestData['method'] === '-') {
                $output['message'] = $weeks->dayUserUnregistrationByTelegram($requestData);
            } else {
                $output['message'] = $weeks->dayUserRegistrationByTelegram($requestData);
            }
        }
    }
}
