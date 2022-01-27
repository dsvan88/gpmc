<?

if (!isset($_POST['message'])) {
    $_POST = json_decode('{"update_id":0,
        "message":{
            "message_id":0,
            "from":{"id":0,"is_bot":false,"first_name":"Dmytro","last_name":"Vankevych","username":"-","language_code":"ru"},
            "chat":{"id":0,"first_name":"Dmytro","last_name":"Vankevych","username":"-","type":"private"},
            "date":0,
            "text":"\/promo тест тайтл\nтест субтайтл\nТест текста, строка 1\nText test, string 2\nТест текста, --строка-- 3",
            "entities":[{"offset":0,"length":6,"type":"bot_command"},{"offset":12,"length":5,"type":"bold"},{"offset":23,"length":8,"type":"italic"},{"offset":37,"length":6,"type":"bold"},{"offset":45,"length":6,"type":"italic"}]}}', true);
    $test = true;
}


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

    $newString = preg_replace(['/\-\-(.*)\-\-/', '/\~\~(.*)\~\~/'], ['<s>$1</s>', '<u>$1</u>'], $newString);

    if ($newString !== '')
        $promoText = $newString;
}
preg_match('/(.*?)\n(.*?)\n([^`]*)/', $promoText, $matches);


$array = [
    'title' => isset($matches[1]) ? $matches[1] : '',
    'subtitle' => isset($matches[2]) ? $matches[2] : '',
    'html' => isset($matches[3]) ? str_replace("\n", '</br>', $matches[3]) : '',
    'type' => 'tg-promo'
];

if ($test) {
    var_dump($output);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tg-message.txt', print_r($_POST['message'], true));
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tg-error.txt', print_r($array, true));
}
/* 
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.news.php';
$news = new News();

$newsData = $news->newsGetAllByType('tg-promo');
if ($newsData) {
    $result = $news->newsUpdate($array, (int) $newsData[0]['id']);
} else {
    $result = $news->newsCreate($array);
} */

$output['message'] = 'Промо-блок, успішно збережений!';
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tg-error.txt', print_r($array, true));
