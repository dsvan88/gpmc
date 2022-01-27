<?
$promoText = trim(mb_substr($_POST['message']['text'], $commandLen + 1, NULL, 'UTF-8'));
preg_match('/^(.*)\r\n(.*)\r\n(.*)$/', $promoText, $matches);
$output['message'] .= json_encode($matches, JSON_UNESCAPED_UNICODE);
$output['message'] .= "\r\n";
$output['message'] .= json_encode($_POST, JSON_UNESCAPED_UNICODE);
