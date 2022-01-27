<?
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


        $adjustOffset = substr_count($_POST['message']['text'], "\\", 0, $_POST['message']['entities'][$i]['offset']);

        $output['message'] .= $offset . ' - ' . $adjustOffset . "\r\n";

        $newString .= mb_substr($_POST['message']['text'], $offset + $adjustOffset, $_POST['message']['entities'][$i]['offset'], 'UTF-8');
        $newString .= "<{$formattings[$_POST['message']['entities'][$i]['type']]}>" . mb_substr($_POST['message']['text'], $_POST['message']['entities'][$i]['offset'] + $adjustOffset, $_POST['message']['entities'][$i]['length'], 'UTF-8') . "</{$formattings[$_POST['message']['entities'][$i]['type']]}>";
        $offset = $_POST['message']['entities'][$i]['offset'] + $adjustOffset + $_POST['message']['entities'][$i]['length'];
    }
    if ($newString !== '')
        $output['message'] .= $newString;
}
$output['message'] .= "\r\n";
$output['message'] .= json_encode($matches, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= json_encode($_POST, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= $_POST['message']['text'];
