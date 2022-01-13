<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegram = isset($_POST['message']['from']['username']) ? $_POST['message']['from']['username'] : '';

$telegramId = $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name', 'telegram'], ['telegramid' => $telegramId]);

if (isset($userData['name']) && $userData['name'] !== 'tmp_telegram_user') {

    if ($telegram !== '' && $userData['telegram'] !== $telegram) {
        $users->userUpdateData(['telegram' => $telegram], ['id' => $userExistsData['id']]);
    }

    $output['message'] = "Я уже запомнил Вас под именем <b>$userData[name]</b>!\r\nЕсли это не Вы - обратитесь к администраторам!";
} else {
    $username = trim(implode(' ', $args));
    if (preg_match('/([^а-яА-ЯрРсСтТуУфФчЧхХШшЩщЪъЫыЬьЭэЮюЄєІіЇїҐґ ])/', $username) === 1) {
        $output['message'] = "Не верный формат псевдонима!\r\nПожалуйста, используйте только <b>кириллицу</b> и <b>пробелы</b> в Вашем псевдониме!";
    } elseif (mb_strlen(trim($username), 'UTF-8') < 2) {
        $output['message'] = "Слишком короткий псевдоним!\r\nПожалуйста, используйте, минимум <b>2</b> символа, что бы люди смогли Вас узнать!";
    } else {
        $userId = $users->userGetId($username);
        $userExistsData = $users->usersGetData(['id', 'name', 'telegramid'], ['id' => $userId]);
        if (isset($userExistsData['id'])) {
            if ($userExistsData['telegramid'] !== '') {
                if ($userExistsData['telegramid'] !== $telegramId) {
                    $output['message'] = "Игрок с этим псевдонимом - уже <b>зарегистрировал</b> себе телеграм!\r\nЕсли это Ваш псевдоним - обратитесь к администраторам!";
                } else {
                    $output['message'] = 'Ваша информация - уже успешно сохранена!';
                }
            } else {
                $users->userUpdateData(['telegram' => $telegram, 'telegramid' => $telegramId], ['id' => $userExistsData['id']]);
                $output['message'] = "Я запомнил Вас под именем <b>$username</b>!\r\nЕсли это не Ваш псевдоним - обратитесь к администраторам!";
            }
            if (isset($userData['id'])) {
                $users->userDelete($userData['id']);
            }
        } else {
            if (isset($userData['id'])) {
                $users->userUpdateData(['name' => $username], ['id' => $userData['id']]);
            } else {
                $users->usersSaveNameFromTelegram(['name' => $username, 'telegram' => $telegram, 'telegramid' => $telegramId]);
            }
            $output['message'] = "Я запомнил Вас под именем <b>$username</b>!\r\nЕсли это не Ваш псевдоним - обратитесь к администраторам!";
        }
    }
}
