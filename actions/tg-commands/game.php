<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegramId = $_POST['message']['from']['username'] === '' ? $_POST['message']['from']['id'] : $_POST['message']['from']['username'];

$userData = $users->usersGetData(['id', 'name'], ['telegram' => $telegramId]);

if (!isset($userData['id'])) {
    // $output['message'] = "Извините! Не узнаю вас в гриме:(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
    $users->usersSaveUnknowTelegram($telegramId);
} elseif ($userData['name'] === 'tmp_telegram_user') {
    // $output['message'] = "Извините! Не узнаю вас в гриме :(\r\nСкажите Ваш псевдоним в игре, что бы я вас запомнил! Напишите: /nick Ваш_псевдоним";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.evenings.php';

    $evenings = new Evenings;

    if ($buffer[0] === '-') {
        // $output['message'] = $evenings->eveningsParticipantUnbookedByTelegram($command, $userData);
    } else {
        $userData['arrive'] = '';
        if (isset($buffer[1]))
            $userData['arrive'] = $buffer[1];
        $userData['duration'] = 0;
        // $output['message'] = $evenings->eveningsParticipantBookedByTelegram($command, $userData);
    }
}

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
            ['вт'],
            ['ср'],
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
$output['message'] = '123' . json_encode($requestData, JSON_UNESCAPED_UNICODE);
/* $method = $time = $date = '';
$dayNum = -1;

$requestData = [
    'method' => $method,
    'time' => $time,
    'date' => $date,
    'dayNum' => $dayNum
];

for ($x = 0; $x < count($args); $x++) {
    $args[$x] = trim($args[$x]);
    if ($args[$x] === '-') {
        $buffer[0] = '-';
    } else {
        $buffer[1] = $args[$x];
    }
}
 */