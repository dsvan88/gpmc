<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$user = new Users();

$userId = $_SESSION['id'];
if (isset($_POST['uid']) && $_SESSION['id'] != $_POST['uid']){
    if ($_SESSION['status'] !== 'admin'){
        die('{"error":1,"text":"Ви не можете змінювати інформацію других користувачів"}');
    }
    $userId = $_POST['uid'];
}

$array=[
    'fio' => trim($_POST['fio']),
    'birthday' => strtotime(trim($_POST['birthday'])),
    'gender' => trim($_POST['gender']),
    'email' => trim($_POST['email']),
    'telegram' => trim($_POST['telegram'])
];

$user->userUpdateData($array, ['id'=>$userId]);

$output['text'] = 'Дані збережено!';