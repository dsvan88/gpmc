<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

$action = new Evenings;

$data = [
    'eid' => (int) $_POST['eid'],
    'game' => trim($_POST['game']),
    'date' => strtotime($_POST['eve_date']),
    'place' => [ 'name'=>$_POST['eve_place'], 'info'=>$_POST['eve_place_info'] ]
];

if (isset($_POST['participant'])){
    $data['participants'] = [];
    for ($i=0; $i < count($_POST['participant']); $i++) { 
        $data['participants'][] = [
            'name'=>$_POST['participant'][$i],
            'arrive'=>$_POST['arrive'][$i],
            'duration'=>$_POST['duration'][$i],
        ];
    }
}

$action->setEveningApproved($data);

$output['txt'] = 'Затверджено!';
