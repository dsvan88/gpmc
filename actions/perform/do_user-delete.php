<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';
$user = new Users();

$userId = (int) $_POST['uid'];

$result = false;

if ($userId > 1)
    $result = $user->userDelete($userId);

$output['message'] = $result ? 'Користувач успішно видалений!' : 'Визначеного користувача неможливо видалити!';
