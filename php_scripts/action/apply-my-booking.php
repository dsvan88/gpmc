<?php
$result = array(
	'error'=> 0,
	'txt' => 'Будемо раді Вас бачити у грі! Будь-ласка, не запізнюйтесь!'
);
$EveningData = $engine->nearEveningGetData(array('id','date','gamers','gamers_info'));
$EveningData['gamers_info'] = json_decode($EveningData['gamers_info']);
$EveningData['gamers_info'][] = [
	'name' => $engine->getGamerName($_SESSION['id']),
	'arrive' =>$_POST['arrive'],
	'duration' =>$_POST['duration']
];
$engine->rowUpdate(['gamers'=>$EveningData['gamers'].','.$_SESSION['id'],'gamers_info'=> json_encode($EveningData['gamers_info'],JSON_UNESCAPED_UNICODE)],['id'=>$EveningData['id']],SQL_TBLEVEN);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));