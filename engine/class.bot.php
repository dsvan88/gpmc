<?php
class MessageBot{

    public $message = '';
    public function prepMessage($message){
        $this->message = $message;
    }
	function sendToTelegramBot($userId)
	{
        $botToken = $this->getAuthData();
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
		curl_setopt_array($curl , $options);
        // $result = curl_exec($curl);
        
        if (is_array($userId) && isset($userId[1])){
            $newParams = $params;
            for($x=1; $x<count($userId); $x++){
                usleep(750000);
                $newParams['chat_id'] = $userId[$x];
                curl_setopt($curl, CURLOPT_POSTFIELDS, $newParams);
                $result[] = json_decode(curl_exec($curl), true);
            }
            return $result;
        }
        else 
            return [json_decode(curl_exec($curl), true)];

	}
    private function getAuthData(){
        require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';

        $settings = new Settings();

        return $settings->settingsGet(['value'],'tg-bot')[0]['value'];
    }
    public function webhookDelete($botToken){
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
		curl_setopt_array($curl , $options);
        $result = json_decode(curl_exec($curl));
        if ($result['ok'])
            return true;
        return false;
    }
    public function webhookSet($botToken){
        $curl = curl_init();
        
        $options = array(
			CURLOPT_URL => "https://api.telegram.org/bot$botToken/setWebhook?url=https://$_SERVER[HTTP_HOST]/tech/tg-bot-webhook.php", // адрес api телеграмм-бота
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,      // максимальное время выполнения запроса
              // CURLOPT_NOBODY => true,  // true для исключения тела ответа из вывода.
			// CURLOPT_FOLLOWLOCATION => 1,
			// CURLOPT_CAINFO => $certs,
			// CURLOPT_CAPATH => $certs,
			// CURLOPT_SSL_VERIFYHOST => 0,			# Если сертификаты не подошли.
			// CURLOPT_SSL_VERIFYPEER => 0,
		);
		curl_setopt_array($curl , $options);
        $result = json_decode(curl_exec($curl));
        if ($result['ok'])
            return true;
        return false;
    }
}