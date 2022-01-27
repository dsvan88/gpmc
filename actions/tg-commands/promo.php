<?
$promoText = trim(mb_substr($_POST['message']['text'], $commandLen + 1, NULL, 'UTF-8'));
preg_match('/(.*?)\n(.*?)\n([^`]*)/', $promoText, $matches);

$entities = [];
for ($i = 0; $i < count($_POST['message']['entities']); $i++) {
    if ($_POST['message']['entities'][$i]['type'] === 'bot_command') continue;
    $entities[$_POST['message']['entities'][$i]['type']] = [
        'offset' => $_POST['message']['entities'][$i]['offset'],
        'length' => $_POST['message']['entities'][$i]['length'],
        'text' => mb_substr($_POST['message']['text'], $_POST['message']['entities'][$i]['offset'], $_POST['message']['entities'][$i]['length'])
    ];
}

$output['message'] .= json_encode($entities, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= json_encode($matches, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= json_encode($_POST, JSON_UNESCAPED_UNICODE);
