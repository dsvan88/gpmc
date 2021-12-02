<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$user = new Users();
$array=[
    'fio' => trim($_POST['fio']),
    'birthday' => strtotime(trim($_POST['birthday'])),
    'gender' => trim($_POST['gender']),
    'email' => trim($_POST['email']),
    'telegram' => trim($_POST['telegram'])
];

$user->userUpdateData($array, ['id'=>$_SESSION['id']]);

$output['text'] = 'Дані збережено!';