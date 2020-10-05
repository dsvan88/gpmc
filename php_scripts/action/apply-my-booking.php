<?php
$result = array(
	'error'=> 0,
	'txt' => 'Будемо раді Вас бачити у грі! Будь-ласка, не запізнюйтесь!'
);
$EveningData = $engine->GetNearEveningData(array('id','date','gamers','gamers_info'));
$EveningData['gamers_info'] = json_decode($EveningData['gamers_info']);
$EveningData['gamers_info'][] = [
	'name' => $engine->GetGamerName($_SESSION['id']),
	'arrive' =>$_POST['arrive'],
	'duration' =>$_POST['duration']
];
$engine->UpdateRow(['gamers'=>$EveningData['gamers'].','.$_SESSION['id'],'gamers_info'=> str_replace('"','\"',json_encode($EveningData['gamers_info'],JSON_UNESCAPED_UNICODE))],['id'=>$EveningData['id']],MYSQL_TBLEVEN);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));