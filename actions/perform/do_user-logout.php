<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
$user = new Users();

$user->logout();