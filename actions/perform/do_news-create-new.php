<?php
if ($_POST['type'] === 'tg-info') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.settings.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.bot.php';

    $settings = new Settings;
    $telegramBot = new MessageBot;

    $message = "<u><strong>$_POST[title]</strong></u>\r\n<em>$_POST[subtitle]</em>\r\n\r\n";
    $message .= preg_replace('/(<((?!b|u|s|strong|em|i|\/b|\/u|\/s|\/strong|\/em|\/i)[^>]+)>)/i', '', str_replace(['<br />', '<br/>', '<br>', '</p>'], "\r\n", trim($_POST['html'])));

    $telegramBot->prepMessage($message);

    $chatsData = $settings->settingsGet(['id', 'value'], 'tg-pinned');
    if ($chatsData) {
        for ($i = 0; $i < count($chatsData); $i++) {
            if ($chatsData[$i]['value'][0] !== '-') continue;
            $chatsId[] = substr($chatsData[$i]['value'], 0, strpos($chatsData[$i]['value'], ':'));
        }
    }
    $result = $telegramBot->sendToTelegramBot($chatsId);
    $output['message'] = 'Телеграм-сповіщення успішно надіслані!';
    for ($i = 0; $i < count($result); $i++) {
        if (!$result[$i]['ok']) {
            $output['error'] = 1;
            $output['message'] = $result[$i]['description'];
            break;
        }
    }
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/telegram_results.txt', print_r($result, false));
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.news.php';

    $news = new News();
    $array = [
        'title' => trim($_POST['title']),
        'subtitle' => trim($_POST['subtitle']),
        'html' => trim($_POST['html']),
        'type' => trim($_POST['type'])
    ];
    if ($_FILES['logo']['name'] != '') {
        $newFilename = FILE_MAINGALL . 'news/' . sha1_file($_FILES['logo']['tmp_name']) . mb_substr($_FILES['logo']['name'], mb_strripos($_FILES['logo']['name'], '.', 0, 'UTF-8'), NULL, 'UTF-8');
        copy($_FILES['logo']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $newFilename);
        $array['logo'] = $newFilename;
    }
    $result = $news->newsCreate($array);
    $output['message'] = 'Новина успішно зафіксована';
}
