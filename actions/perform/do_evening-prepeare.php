<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

$action = new Evenings;

$data = [
    'eid' => (int) $_POST['eid'],
    'game' => trim($_POST['game']),
    'date' => strtotime($_POST['eve_date']),
    'place' => [ 'name'=>$_POST['eve_place'], 'info'=>$_POST['eve_place_info'] ]
];

$action->setEveningApproved($data);

$output['txt'] = 'Затверджено!';
