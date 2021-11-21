<?php
/* if (count($_POST)===0){
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/init-form.php';
    $template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/main-template.html');
    die(str_replace(array_keys($output),array_values($output),$template));
} */

require __DIR__.'/class.action.php';

$action = new Action();

$action->DBInit();