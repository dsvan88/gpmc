<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$users = new Users();

$output['result'] = $users->usersGetNameAutoComplete($_POST['term']);