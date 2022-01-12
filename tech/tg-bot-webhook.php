<?

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (strpos($contentType, 'application/json') !==  false) {
    $data = trim(file_get_contents('php://input'));
    $_POST = json_decode($data, true);

    if (!is_array($_POST)) {
        error_log(json_encode($_POST, JSON_UNESCAPED_UNICODE));
        die('{"error":"1","title":"Error!","text":"Error: Nothing to send."}');
    }
}

require $_SERVER['DOCUMENT_ROOT'] . '/engine/class.action.php';
require $_SERVER['DOCUMENT_ROOT'] . '/engine/class.settings.php';
require $_SERVER['DOCUMENT_ROOT'] . '/engine/class.bot.php';

$GLOBALS['CommonActionObject'] = new Action;
$bot = new MessageBot();
$settings = new Settings();

$_POST['message']['text'] = trim($_POST['message']['text']);

$output['message'] = '';

if (preg_match('/^(\+|-)\s{0,3}(пн|пон|вт|ср|чт|чет|пт|пят|сб|суб|вс|вос)/', mb_strtolower($_POST['message']['text'], 'UTF-8')) === 1) {
    $command = 'booking';
    preg_match_all('/(\+|-)\s{0,3}(пн|пон|вт|ср|чт|чет|пт|пят|сб|суб|вс|вос)|(\d{2}\:\d{2})|(\d{1,2}\.\d{1,2})|(\d{1}\-\d{1})/i', mb_strtolower($_POST['message']['text'], 'UTF-8'), $matches);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/actions/tg-commands/game.php';
} elseif (strpos($_POST['message']['text'], '/') === 0) {
    $command = mb_substr($_POST['message']['text'], 1, NULL, 'UTF-8');

    $spacePos = mb_strpos($command, ' ', 0, 'UTF-8');
    if ($spacePos !== false) {
        $command = mb_substr($command, 0, $spacePos, 'UTF-8');
    }

    /*  if (in_array($command, ['mafia', 'poker', 'cash'])) {
        preg_match_all('/\s(\+|-)|(\d{2}\:\d{2})|(\d{1,2}\.\d{1,2})/', mb_substr($_POST['message']['text'], mb_strlen($command), NULL, 'UTF-8'), $matches);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/actions/tg-commands/game.php';
    } else */
    if (in_array($command, ['?', 'help'])) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/actions/tg-commands/help.php';
    } elseif (file_exists("$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php")) {
        preg_match_all('/([a-zA-Zа-яА-ЯрРсСтТуУфФчЧхХШшЩщЪъЫыЬьЭэЮюЄєІіЇїҐґ]+)/', mb_substr($_POST['message']['text'], mb_strlen($command) + 1, NULL, 'UTF-8'), $matches);
        $args = $matches[0];
        require_once "$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/$command.php";
    } else {
        $output['message'] = 'Команда не знайдена';
    }
}

if ($output['message'] !== '') {
    $bot->prepMessage($output['message']);
    try {
        $result = $bot->sendToTelegramBot($_POST['message']['chat']['id']);

        // if ($_POST['message']['chat']['type'] !== 'private') {
        $chatId = $result[0]['result']['chat']['id'];
        $messageId = $result[0]['result']['message_id'];

        if ($command === 'near') {
            $bot->pinTelegramBotMessageAndSaveItsData($chatId, $messageId);
        } else if ($command === 'booking') {
            require_once "$_SERVER[DOCUMENT_ROOT]/actions/tg-commands/near.php";
            $result = $bot->editPinnedMessage($chatId, $output['message']);
        }
        // }
    } catch (Exception $e) {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tg-error.txt', print_r($_POST, true));
    }
}
