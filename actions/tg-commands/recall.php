<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegramId = $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name', 'telegram', 'status'], ['telegramid' => $telegramId]);

if (!isset($userData['id'])) {
    $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш псевдоним (кириллицей)";
} elseif (!in_array($userData['status'], ['manager', 'admin'], true)) {
    $output['message'] = "Команда не знайдена";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';

    $weeks = new Weeks;

    $dayName = '';
    $requestData['dayNum'] = -1;
    $requestData['currentDay'] = getdate()['wday'] - 1;

    if ($requestData['currentDay'] === -1)
        $requestData['currentDay'] = 6;

    if (count($args) > 0) {

        for ($i = 0; $i < count($args); $i++) {
            if (preg_match_all('/^(пн|пон|вт|ср|чт|чет|пт|пят|сб|суб|вс|вос|сг|сег|зав)/', trim(mb_strtolower(str_replace(' на ', '', $args[$i])), 'UTF-8'), $daysPattern) !== 0) {
                $dayName = $daysPattern[1][0];
                $output['message'] .= json_encode($daysPattern, JSON_UNESCAPED_UNICODE);
                break;
            }
        }
    }
    if (in_array($dayName, ['', 'сг', 'сег'], true)) {
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

    $output['message'] .= json_encode($requestData, JSON_UNESCAPED_UNICODE);
}
