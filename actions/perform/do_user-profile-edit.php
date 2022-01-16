<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$user = new Users();

$userId = $_SESSION['id'];
if (isset($_POST['uid']) && $_SESSION['id'] != $_POST['uid']) {
    if ($_SESSION['status'] !== 'admin') {
        die('{"error":1,"text":"Ви не можете змінювати інформацію других користувачів"}');
    }
    $userId = $_POST['uid'];
}

$birthday = strtotime(trim($_POST['birthday']));

if ($birthday > $_SERVER['REQUEST_TIME'] - 60 * 60 * 24 * 365)
    $birthday = 0;

$array = [
    'fio' => trim($_POST['fio']),
    'birthday' => $birthday,
    'gender' => trim($_POST['gender']),
    'email' => trim($_POST['email']),
    'telegram' => trim($_POST['telegram']),
    'telegramid' => trim($_POST['telegramid'])
];

if (isset($_POST['status']))
    $array['status'] = trim($_POST['status']);

$user->userUpdateData($array, ['id' => $userId]);

$output['text'] = 'Дані збережено!';
