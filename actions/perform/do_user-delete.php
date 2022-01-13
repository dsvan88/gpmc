<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';
$user = new Users();

if ($_SESSION['status'] !== 'admin') {
    die('{"error":1,"text":"Ви не можете видаляти користувачів!"}');
}

$userId = (int) $_POST['uid'];

$result = false;

if ($userId > 1)
    $result = $user->userDelete($userId);

$output['message'] = $result ? 'Користувач успішно видалений!' : 'Визначеного користувача неможливо видалити!';
