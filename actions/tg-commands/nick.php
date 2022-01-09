<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$users = new Users;

$telegramId = isset($_POST['message']['from']['username']) ? $_POST['message']['from']['username'] : $_POST['message']['from']['id'];

$userData = $users->usersGetData(['id', 'name'], ['telegram' => $telegramId]);

if (isset($userData['name']) && $userData['name'] !== 'tmp_telegram_user') {
    $output['message'] = "Я уже запомнил Вас под именем <b>$userData[name]</b>!\r\nЕсли это не Вы - обратитесь к администраторам!";
} else {
    $username = trim(implode(' ', $args));
    $userId = $users->userGetId($username);
    $userExistsData = $users->usersGetData(['id', 'name', 'telegram'], ['id' => $userId]);
    if (isset($userExistsData['id'])) {
        if ($userExistsData['telegram'] !== '') {
            if ($userExistsData['telegram'] !== $telegramId)
                $output['message'] = "Игрок с этим псевдонимом - уже <b>зарегистрировал</b> себе телеграм!\r\nЕсли это Ваш псевдоним - обратитесь к администраторам!";
            else
                $output['message'] = 'Ваша информация - уже успешно сохранена!';
        } else {
            $users->userUpdateData(['telegram' => $telegramId], ['id' => $userExistsData['id']]);
            if (isset($userData['id']))
                $users->userDelete($userData['id']);
            $output['message'] = "Я запомнил Вас под именем <b>$username</b>!\r\nЕсли это не Ваш псевдоним - обратитесь к администраторам!";
        }
    } else {
        if (isset($userData['id'])) {
            $users->userUpdateData(['name' => $username], ['id' => $userData['id']]);
        } else {
            $users->usersSaveNameFromTelegram(['name' => $username, 'telegram' => $telegramId]);
        }
        $output['message'] = "Я запомнил Вас под именем <b>$username</b>!\r\nЕсли это не Ваш псевдоним - обратитесь к администраторам!";
    }
}
$output['message'] .= "\r\n$telegramId";
