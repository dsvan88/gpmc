<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.places.php';

$weeks = new Weeks;

$weekData = $weeks->getDataByTime();

$gameNames = [
    'mafia' => 'Мафия',
    'poker' => 'Покер',
    'cash' => 'Кеш-покер'
];

if ($weekData) {
    $output['message'] = '';
    for ($i = 0; $i < 7; $i++) {

        if (!isset($weekData['data'][$i])) {
            continue;
        }
        $format = "d.m.Y {$weekData['data'][$i]['time']}";
        $dayDate = strtotime(date($format, $weekData['start'] + 86400 * $i));
        if (time() > $dayDate) {
            continue;
        }

        $date = str_replace(
            ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            ['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'],
            date('d.m.Y (l) H:i', $dayDate)
        );
        $output['message'] .= "$date - {$gameNames[$weekData['data'][$i]['game']]}\r\n\r\n";
        for ($x = 0; $x < count($weekData['data'][$i]['participants']); $x++) {
            $output['message'] .= ($x + 1) . ". <b>{$weekData['data'][$i]['participants'][$x]['name']}</b> {$weekData['data'][$i]['participants'][$x]['arrive']}\r\n";
        }
        $output['message'] .= "____\r\n";
    }
} else {
    $output['message'] = "Пока вечера игр не запланированны!\r\nПопробуйте позднее";
}
