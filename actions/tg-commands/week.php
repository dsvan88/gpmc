<?php
if (!isset($_POST['message'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.action.php';
    $GLOBALS['CommonActionObject'] = new Action;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.news.php';

$weeks = new Weeks;
$news = new News;

$weeksData = $weeks->getNearWeeksDataByTime();

$gameNames = [
    'mafia' => 'Мафия 🎭',
    'poker' => 'Покер ♦️',
    'board' => 'Настолки 🎲',
    'cash' => 'Кеш-покер 🃏'
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
$output['message'] = '';
foreach ($weeksData as $weekData) {

    for ($i = 0; $i < 7; $i++) {

        if (!isset($weekData['data'][$i])) {
            continue;
        }
        $format = "d.m.Y {$weekData['data'][$i]['time']}";
        $dayDate = strtotime(date($format, $weekData['start'] + TIMESTAMP_DAY * $i));

        if ($_SERVER['REQUEST_TIME'] > $dayDate + DATE_MARGE || $weekData['data'][$i]['status'] === 'recalled') {
            continue;
        }

        $date = str_replace(
            ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            ['<b>Понедельник</b>', '<b>Вторник</b>', '<b>Среда</b>', '<b>Четверг</b>', '<b>Пятница</b>', '<b>Суббота</b>', '<b>Воскресенье</b>'],
            date('d.m.Y (l) H:i', $dayDate)
        );
        // $output['message'] .= "$date - {$gameNames[$weekData['data'][$i]['game']]} ({$costs[$weekData['data'][$i]['game']]})\r\n";
        $output['message'] .= "$date - {$gameNames[$weekData['data'][$i]['game']]}\r\n";

        if (in_array('fans', $weekData['data'][$i]['mods'], true))
            $output['message'] .= "*<b>ФАНОВАЯ</b>! Хорошо проведите время и повеселитесь!\r\n";
        if (in_array('tournament', $weekData['data'][$i]['mods'], true))
            $output['message'] .= "<b>ТУРНИР</b>! Станьте чемпионом в равной борьбе!\r\n";
        if (isset($weekData['data'][$i]['prim']) && $weekData['data'][$i]['prim'] !== '')
            $output['message'] .= "<u>{$weekData['data'][$i]['prim']}</u>\r\n";

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
                $modsData = " (<i>$modsData</i>)";
            $output['message'] .= ($x + 1) . ". <b>{$weekData['data'][$i]['participants'][$x]['name']}</b>{$modsData}\r\n";
        }
        $output['message'] .= "___________________________\r\n";
    }
}

if ($output['message'] === '') {
    $output['message'] = "В ближайшее время, игры не запланированны!\r\nОбратитесь к нам позднее.\r\n";
}

$promoData = $news->newsGetAllByType('tg-promo');
if ($promoData) {
    if ($promoData[0]['title'] !== '') {
        $promoData = $promoData[0];
        $message = "<u><b>$promoData[title]</b></u>\r\n<i>$promoData[subtitle]</i>\r\n\r\n";
        $message .= preg_replace('/(<((?!b|u|s|strong|em|i|\/b|\/u|\/s|\/strong|\/em|\/i)[^>]+)>)/i', '', str_replace(['<br />', '<br/>', '<br>', '</p>'], "\r\n", trim($promoData['html'])));
        $output['message'] .= "\r\n$message";
    }
}

if (!isset($_POST['message'])) {
    print_r($output);
}
