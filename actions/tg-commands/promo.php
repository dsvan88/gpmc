<?
if (!isset($_POST['message'])) {
    $_POST = json_decode('{"update_id":314931246,"message":{"message_id":626,"from":{"id":900669168,"is_bot":false,"first_name":"Dmytro","last_name":"Vankevych","username":"dsvan88","language_code":"ru"},"chat":{"id":900669168,"first_name":"Dmytro","last_name":"Vankevych","username":"dsvan88","type":"private"},"date":1643312770,"text":"\/promo тест тайтл\nтест субтайтл\nТест текста, строка 1\nText test, string 2\nТест текста, строка 3","entities":[{"offset":0,"length":6,"type":"bot_command"},{"offset":12,"length":5,"type":"bold"},{"offset":23,"length":8,"type":"italic"},{"offset":37,"length":6,"type":"bold"},{"offset":45,"length":6,"type":"italic"}]}}', true);
    $test = true;
}

$promoText = trim(mb_substr($_POST['message']['text'], $commandLen + 1, NULL, 'UTF-8'));
preg_match('/(.*?)\n(.*?)\n([^`]*)/', $promoText, $matches);

if (isset($_POST['message']['entities'])) {

    $newString = '';
    $offset = 0;
    $formattings = [
        'bold' => 'b',
        'italic' => 'i',
    ];
    for ($i = 0; $i < count($_POST['message']['entities']); $i++) {
        if ($_POST['message']['entities'][$i]['type'] === 'bot_command') continue;


        $adjustOffset = substr_count($_POST['message']['text'], "\n", 0, $_POST['message']['entities'][$i]['offset']);

        $output['message'] .= substr($_POST['message']['text'], 0, $_POST['message']['entities'][$i]['offset']);

        $output['message'] .= $offset . ' - ' . $adjustOffset . "\r\n";


        $newString .= mb_substr($_POST['message']['text'], $offset + $adjustOffset, $_POST['message']['entities'][$i]['offset'], 'UTF-8');
        $newString .= "<{$formattings[$_POST['message']['entities'][$i]['type']]}>" . mb_substr($_POST['message']['text'], $_POST['message']['entities'][$i]['offset'] + $adjustOffset, $_POST['message']['entities'][$i]['length'], 'UTF-8') . "</{$formattings[$_POST['message']['entities'][$i]['type']]}>";
        $offset = $_POST['message']['entities'][$i]['offset'] + $adjustOffset + $_POST['message']['entities'][$i]['length'];
    }
    if ($newString !== '')
        $output['message'] .= $newString;
}

if (!$test) {
    print_r($output);
}

/* $output['message'] .= "\r\n";
$output['message'] .= json_encode($matches, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= json_encode($_POST, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= $_POST['message']['text']; */
