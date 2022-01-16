<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.places.php';

$weeks = new Weeks;

$weekData = $weeks->getDataByTime();

$gameNames = [
    'mafia' => 'Мафия',
    'poker' => 'Покер',
    'board' => 'Настолки',
    'cash' => 'Кеш-покер'
];

$durations = [
    '',
    '1-2',
    '2-3',
    '3-4'
];

$costs = [
    'mafia' => 90,
    'poker' => 70,
    'board' => 50,
    'cash' => 400
];

if ($weekData) {
    $output['message'] = '';
    for ($i = 0; $i < 7; $i++) {

        if (!isset($weekData['data'][$i])) {
            continue;
        }
        $format = "d.m.Y {$weekData['data'][$i]['time']}";
        $dayDate = strtotime(date($format, $weekData['start'] + 86400 * $i));

        if ($_SERVER['REQUEST_TIME'] > $dayDate + DATE_MARGE) {
            continue;
        }

        $date = str_replace(
            ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            ['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'],
            date('d.m.Y (l) H:i', $dayDate)
        );
        $output['message'] .= "$date - {$gameNames[$weekData['data'][$i]['game']]} ({$costs[$weekData['data'][$i]['game']]})\r\n";

        if (in_array('fans', $weekData['data'][$i]['mods'], true))
            $output['message'] .= "*<b>ФАНОВАЯ</b>! Хорошо проведите время и повеселитесь!\r\n";
        if (in_array('tournament', $weekData['data'][$i]['mods'], true))
            $output['message'] .= "<b>ТУРНИР</b>! Станьте чемпионом в равной борьбе!\r\n";

        $output['message'] .= "\r\n";

        for ($x = 0; $x < count($weekData['data'][$i]['participants']); $x++) {
            $modsData = '';
            if ($weekData['data'][$i]['participants'][$x]['arrive'] !== '' && $weekData['data'][$i]['participants'][$x]['arrive'] !== $weekData['data'][$i]['time']) {
                $modsData .= $weekData['data'][$i]['participants'][$x]['arrive'];
                if ($weekData['data'][$i]['participants'][$x]['duration'] != 0) {
                    $modsData .= ', ';
                }
            }
            if ($weekData['data'][$i]['participants'][$x]['duration'] != 0) {
                $modsData .= "на {$durations[$weekData['data'][$i]['participants'][$x]['duration']]} игры";
            }
            if ($modsData !== '')
                $modsData = "(<i>$modsData</i>)";
            $output['message'] .= ($x + 1) . ".\t<b>{$weekData['data'][$i]['participants'][$x]['name']}</b> {$modsData}\r\n";
        }
        $output['message'] .= "____\r\n";
    }
} else {
    $output['message'] = "Пока вечера игр не запланированны!\r\nПопробуйте позднее";
}

$promoData = $settings->settingsGet('value', 'tg-promo');
if ($promoData) {
    $output['message'] .= "___________\r\n\r\n{$promoData[0]['value']}";
}
