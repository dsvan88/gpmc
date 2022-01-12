<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.settings.php';

class MessageBot
{
    public $message = '';
    public $settings;
    private $botToken = '';
    public function prepMessage($message)
    {
        $this->settings = new Settings();
        $this->message = $message;
        $this->botToken = $this->getAuthData();
    }
    public function sendToTelegramBot($userId)
    {
        $botToken = $this->botToken;
        $params = array(
            'chat_id' => is_array($userId) ? $userId[0] : $userId, // id получателя сообщения
            'text' => $this->message, // текст сообщения
            'parse_mode' => 'HTML', // режим отображения сообщения, не обязательный параметр
        );
        $result = [];
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.telegram.org/bot$botToken/sendMessage", // адрес api телеграмм-бота
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,       // отправка данных методом POST
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
            CURLOPT_POSTFIELDS => $params,   // параметры запроса
            // CURLOPT_NOBODY => true,  // true для исключения тела ответа из вывода.
            // CURLOPT_FOLLOWLOCATION => 1,
            // CURLOPT_CAINFO => $certs,
            // CURLOPT_CAPATH => $certs,
            // CURLOPT_SSL_VERIFYHOST => 0,			# Если сертификаты не подошли.
            // CURLOPT_SSL_VERIFYPEER => 0,
        );
        curl_setopt_array($curl, $options);
        // $result = json_decode(curl_exec($curl), true);

        if (is_array($userId) && isset($userId[1])) {
            $newParams = $params;
            for ($x = 1; $x < count($userId); $x++) {
                usleep(750000);
                $newParams['chat_id'] = $userId[$x];
                curl_setopt($curl, CURLOPT_POSTFIELDS, $newParams);
                $result[] = json_decode(curl_exec($curl), true);
            }
            return $result;
        } else
            return [json_decode(curl_exec($curl), true)];
    }
    private function getAuthData()
    {
        return $this->settings->settingsGet(['value'], 'tg-bot')[0]['value'];
    }
    public function webhookDelete($botToken)
    {
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.telegram.org/bot$botToken/deleteWebhook", // адрес api телеграмм-бота
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
            // CURLOPT_NOBODY => true,  // true для исключения тела ответа из вывода.
            // CURLOPT_FOLLOWLOCATION => 1,
            // CURLOPT_CAINFO => $certs,
            // CURLOPT_CAPATH => $certs,
            // CURLOPT_SSL_VERIFYHOST => 0,			# Если сертификаты не подошли.
            // CURLOPT_SSL_VERIFYPEER => 0,
        );
        curl_setopt_array($curl, $options);
        $result = json_decode(curl_exec($curl), true);
        if ($result['ok'])
            return true;
        return false;
    }
    public function webhookSet($botToken)
    {
        if (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) !== 'https')
            return false;
        $curl = curl_init();

        $options = array(
            CURLOPT_URL => "https://api.telegram.org/bot$botToken/setWebhook?url=https://$_SERVER[HTTP_HOST]/tech/tg-bot-webhook.php", // адрес api телеграмм-бота
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
        );
        curl_setopt_array($curl, $options);
        $result = json_decode(curl_exec($curl), true);
        if ($result['ok'])
            return true;
        return false;
    }
    public function pinTelegramBotMessage($chatId, $messageId)
    {
        $botToken = $this->botToken;
        $params = array(
            'chat_id' => $chatId, // id чата
            'message_id' => $messageId, // id закрепляемого сообщения
            'disable_notification' => true, // "Тихий" метод закрепления, без оповещения в чате об этом
        );

        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.telegram.org/bot$botToken/pinChatMessage", // адрес api телеграмм-бота
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,       // отправка данных методом POST
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
            CURLOPT_POSTFIELDS => $params,   // параметры запроса
        );

        curl_setopt_array($curl, $options);
        $result = json_decode(curl_exec($curl), true);
        if ($result['ok'])
            return true;
        return false;
    }
    public function pinTelegramBotMessageAndSaveItsData($chatId, $messageId)
    {
        $this->pinTelegramBotMessage($chatId, $messageId);

        $chatData = $this->settings->settingsGet(['id', 'value'], 'tg-pinned');
        $i = -1;

        if (isset($chatData[0]['value'])) {
            while (isset($chatData[++$i])) {
                $chatData[$i]['value'] = explode(':', $chatData[$i]['value']);
                if ($chatData[$i]['value'][0] == $chatId) {
                    if ($chatData[$i]['value'][0] != $messageId) {
                        $this->settings->settingsSet(['value' => "$chatId:$messageId", 'value'], $chatData[$i]['id']);
                    }
                    $i = false;
                    break;
                }
            }
        }

        if (!isset($chatData[0]['value']) || is_numeric($i)) {
            $this->settings->settingsSet([
                'type' => 'tg-pinned',
                'short_name' => 'telegram_pinned-message',
                'name' => 'Закреплённое сообщение в чате',
                'value' => "$chatId:$messageId"
            ]);
        }
    }
    public function editPinnedMessage($chatId, $message)
    {
        $chatData = $this->settings->settingsGet(['id', 'value'], 'tg-pinned');
        $i = -1;

        if (isset($chatData[0]['value'])) {
            while (isset($chatData[++$i])) {
                $chatData[$i]['value'] = explode(':', $chatData[$i]['value']);
                if ($chatData[$i]['value'][0] == $chatId) {
                    return $this->editMessage($chatId, $chatData[$i]['value'][1], $message);
                }
            }
        }
        return false;
    }
    public function editMessage($chatId, $messageId, $message)
    {
        $botToken = $this->botToken;
        $params = array(
            'chat_id' => $chatId, // id получателя сообщения
            'message_id' => $messageId, // id сообщения
            'text' => $message, // текст сообщения
            'parse_mode' => 'HTML', // режим отображения сообщения, не обязательный параметр
        );

        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.telegram.org/bot$botToken/editMessageText", // адрес api телеграмм-бота
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,       // отправка данных методом POST
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
            CURLOPT_POSTFIELDS => $params,   // параметры запроса
            // CURLOPT_NOBODY => true,  // true для исключения тела ответа из вывода.
            // CURLOPT_FOLLOWLOCATION => 1,
            // CURLOPT_CAINFO => $certs,
            // CURLOPT_CAPATH => $certs,
            // CURLOPT_SSL_VERIFYHOST => 0,			# Если сертификаты не подошли.
            // CURLOPT_SSL_VERIFYPEER => 0,
        );
        curl_setopt_array($curl, $options);
        $result = json_decode(curl_exec($curl), true);
        return $result;
    }
}
