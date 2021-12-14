<?

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
// if (strpos($contentType,'application/json') !==  false) {
// 	$_POST = trim(file_get_contents('php://input'));
// 	$_POST = json_decode($_POST, true);

// 	if(!is_array($_POST)){
// 		error_log(json_encode($_POST,JSON_UNESCAPED_UNICODE));
//         die('{"error":"1","title":"Error!","text":"Error: Nothing to send."}');
//     }
// }


// $array=[
//     'name'=>"{$_POST['message']['from']['first_name']} {$_POST['message']['from']['last_name']} (@{$_POST['message']['from']['username']}, id:{$_POST['message']['from']['id']})",
//     'message'=> $_POST['message']['text']
// ];

require $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
// require $_SERVER['DOCUMENT_ROOT'].'/engine/class.bot.php';
$GLOBALS['CommonActionObject'] = new Action;
// $bot = new MessageBot();

$jsonString = '{
    "update_id":834263384,
    "message":{
        "message_id":41,
        "from":{
            "id":900669168,
            "is_bot":"",
            "first_name":"Dmytro",
            "last_name":"Vankevych",
            "username":"dsvan881",
            "language_code":"ru"
        },
        "chat":{
            "id":-626874720,
            "title":"TestGroup",
            "type":"group",
            "all_members_are_administrators":1
        },
        "date":1637502053,
        "text":"/mafia + планирую прибить Джокера и прибыть на 18:30"
    }
}';

$_POST = json_decode($jsonString,true);

$_POST['message']['text'] = trim($_POST['message']['text']);

if (strpos($_POST['message']['text'],'/') === 0){
    $command = mb_substr($_POST['message']['text'], 1, NULL, 'UTF-8');

    $spacePos = mb_strpos($command, ' ', 0, 'UTF-8');
    if ($spacePos !== false){
        $command = mb_substr($command, 0, $spacePos, 'UTF-8');
    }

    if (in_array($command,['mafia','poker','cash'])){
        preg_match_all('/(\+|\-)|(\d{2}\:\d{2})/',mb_substr($_POST['message']['text'],mb_strlen($command),NULL,'UTF-8'), $matches);
        $args = $matches[0];
        require_once $_SERVER['DOCUMENT_ROOT'].'/actions/tg-commands/game.php';
    }
}
print_r($output);
/* 


$bot->prepMessage("I catch your message Mr. $array[name], you said: $array[message]");

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