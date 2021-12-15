<?

/* $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (strpos($contentType,'application/json') !==  false) {
	$_POST = trim(file_get_contents('php://input'));
	$_POST = json_decode($_POST, true);

	if(!is_array($_POST)){
		error_log(json_encode($_POST,JSON_UNESCAPED_UNICODE));
        die('{"error":"1","title":"Error!","text":"Error: Nothing to send."}');
    }
} */

require $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
require $_SERVER['DOCUMENT_ROOT'].'/engine/class.bot.php';
$GLOBALS['CommonActionObject'] = new Action;
$bot = new MessageBot(); 

$_POST['message']['text'] = trim($_POST['message']['text']);

if (strpos($_POST['message']['text'],'/') === 0){
    $command = mb_substr($_POST['message']['text'], 1, NULL, 'UTF-8');

    $spacePos = mb_strpos($command, ' ', 0, 'UTF-8');
    if ($spacePos !== false){
        $command = mb_substr($command, 0, $spacePos, 'UTF-8');
    }

    if (in_array($command,['mafia','poker','cash'])){
        preg_match_all('/\s(\+|-)|(\d{2}\:\d{2})/',mb_substr($_POST['message']['text'],mb_strlen($command),NULL,'UTF-8'), $matches);
        $args = $matches[0];
        require_once $_SERVER['DOCUMENT_ROOT'].'/actions/tg-commands/game.php';
    }
    elseif (in_array($command,['?','help'])){
        require_once $_SERVER['DOCUMENT_ROOT'].'/actions/tg-commands/help.php';
    }
    elseif (file_exists("$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php")){
        preg_match_all('/([a-zA-Zа-яА-ЯрРсСтТуУфФчЧхХШшЩщЪъЫыЬьЭэЮюЄєІіЇїҐґ]+)/',mb_substr($_POST['message']['text'],mb_strlen($command)+1,NULL,'UTF-8'), $matches);
        $args = $matches[0];
        require_once "$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php";
    }
    else
        $output['message'] = 'Команда не знайдена';
}

$bot->prepMessage($output['message']);
try {
    $bot->sendToTelegramBot($_POST['message']['chat']['id']);
}
catch (Exception $e) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tg-error.txt',print_r($_POST,true));
}

// file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tg-message.txt',print_r($_POST,true).' '.$command);
/* $bot->prepMessage("I catch your message Mr. $array[name], you said: $array[message]");

try {
    $bot->sendToTelegramBot($_POST['message']['chat']['id']);
}
catch (Exception $e) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tg-error.txt',print_r($POST,true));
}

require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

$settings = new Settings();
$chatId = $settings->settingsGet(['id','value'],['tg-chat'],['value'=>$_POST['message']['chat']['id']]);

if (!isset($chatId[0])){
    $id = "add";
    $array=[
            'type' => 'tg-chat',
            'short_name' => 'tg-chat-id',
            'name' => 'Телеграм чат з '.(isset($_POST['message']['chat']['title']) ? $_POST['message']['chat']['title'] : $array['name']),
            'value' => $_POST['message']['chat']['id'],
            'by_default' => $_POST['message']['chat']['id']
        ];
    print_r($array);
    // $settings->settingsSet($array,$id);
} */