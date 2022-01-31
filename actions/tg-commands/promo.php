<?
$promoText = trim(mb_substr($_POST['message']['text'], $commandLen + 1, NULL, 'UTF-8'));

if (isset($_POST['message']['entities'])) {

    $newString = '';
    $offset = 0;
    $formattings = [
        'bold' => 'b',
        'italic' => 'i',
        'strikethrough' => 's',
    ];
    for ($i = 0; $i < count($_POST['message']['entities']); $i++) {
        if ($_POST['message']['entities'][$i]['type'] === 'bot_command') {
            $offset = $_POST['message']['entities'][$i]['offset'] + $_POST['message']['entities'][$i]['length'];
            continue;
        }
        $newString .= mb_substr($_POST['message']['text'], $offset, $_POST['message']['entities'][$i]['offset'] - $offset, 'UTF-8');
        $newString .= "<{$formattings[$_POST['message']['entities'][$i]['type']]}>" . mb_substr($_POST['message']['text'], $_POST['message']['entities'][$i]['offset'], $_POST['message']['entities'][$i]['length'], 'UTF-8') . "</{$formattings[$_POST['message']['entities'][$i]['type']]}>";
        $offset = $_POST['message']['entities'][$i]['offset'] + $_POST['message']['entities'][$i]['length'];
    }
    $newString .= mb_substr($_POST['message']['text'], $offset, null, 'UTF-8');

    $newString = preg_replace(['/\-\-(.*)\-\-/'], ['<u>$1</u>'], $newString);

    if ($newString !== '')
        $promoText = $newString;
}
preg_match('/(.*?)\n(.*?)\n([^`]*)/', $promoText, $matches);

$array = [
    'title' => isset($matches[1]) ? trim($matches[1]) : '',
    'subtitle' => isset($matches[2]) ? trim($matches[2]) : '',
    'html' => isset($matches[3]) ? str_replace("\n", '</br>', $matches[3]) : '',
    'type' => 'tg-promo'
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.news.php';
$news = new News();

$newsData = $news->newsGetAllByType('tg-promo');
if ($newsData) {
    $result = $news->newsUpdate($array, (int) $newsData[0]['id']);
} else {
    $result = $news->newsCreate($array);
}

$output['message'] = 'Промо-блок, успішно збережений!';
