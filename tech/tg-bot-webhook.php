<?

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (strpos($contentType,'application/json') !==  false) {
	$_POST = trim(file_get_contents('php://input'));
	$_POST = json_decode($_POST, true);

	if(!is_array($_POST)){
		error_log(json_encode($_POST,JSON_UNESCAPED_UNICODE));
        die('{"error":"1","title":"Error!","text":"Error: Nothing to send."}');
    }
}

require $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
require $_SERVER['DOCUMENT_ROOT'].'/engine/class.bot.php';
$GLOBALS['CommonActionObject'] = new Action;
$bot = new MessageBot();

/*
$jsonString = '{
    "update_id":834263384,
    "message":{
        "message_id":41,
        "from":{
            "id":620991421,
            "is_bot":"",
            "first_name":"Иван",
            "last_name":"Фрай",
            "username":"",
            "language_code":"ru"
        },
        "chat":{
            "id":-626874720,
            "title":"TestGroup",
            "type":"group",
            "all_members_are_administrators":1
        },
        "date":1637502053,
        "text":"/mafia + yf 19:00"
    }
}'; 
$_POST = json_decode($jsonString,true);
*/

$_POST['message']['text'] = trim($_POST['message']['text']);

if (strpos($_POST['message']['text'],'/') === 0){
    $command = mb_substr($_POST['message']['text'], 1, NULL, 'UTF-8');

    $spacePos = mb_strpos($command, ' ', 0, 'UTF-8');
    if ($spacePos !== false){
        $command = mb_substr($command, 0, $spacePos, 'UTF-8');
    }

    if (in_array($command,['mafia','poker','cash'])){
        preg_match_all('/\s(\+|\-)\s|(\d{2}\:\d{2})/',mb_substr($_POST['message']['text'],mb_strlen($command),NULL,'UTF-8'), $matches);
        $args = $matches[0];
        require_once $_SERVER['DOCUMENT_ROOT'].'/actions/tg-commands/game.php';
    }
    if (file_exists("$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php"))
        require_once "$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php";
    else
        $output['message'] = 'Команда не знайдена';
}

$bot->prepMessage($output['message']);

try {
    // $bot->sendToTelegramBot($_POST['message']['chat']['id']);
    $bot->sendToTelegramBot('900669168');
}
catch (Exception $e) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tg-error.txt',print_r($_POST,true));
}
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tg-message.txt',print_r($_POST,true));
