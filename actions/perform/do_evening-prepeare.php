<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

$action = new Evenings;

$data['date'] = strtotime($_POST['eve_date']);
$data['place'] = ['name'=>$_POST['eve_place'], 'info'=>$_POST['eve_place_info']];

$action->setEveningApproved($data);

$output['txt'] = 'Затверджено!';
