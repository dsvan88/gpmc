<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

$action = new Evenings;

$data = [];

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
$data['date'] = strtotime($_POST['eve_date']);
$data['place'] = [ 'name'=>$_POST['eve_place'], 'info'=>$_POST['eve_place_info'] ];

$action->setEveningApproved($data);

$output['txt'] = 'Затверджено!';
