<?php
$result = array(
	'error'=> 0,
	'txt' => 'Очень жаль!'.PHP_EOL.'Надеемся увидеть Вас в следующий раз!'
);
$EveningData = $engine->nearEveningGetData(array('id','date','gamers','gamers_info'));
$EveningData['gamers'] = explode(',',$EveningData['gamers']);
$EveningData['gamers_info'] = json_decode($EveningData['gamers_info']);

for($x=0;$x<count($EveningData['gamers']);$x++)
{
	if ($EveningData['gamers'][$x] === $_SESSION['id'])
	{
		unset($EveningData['gamers'][$x]);
		unset($EveningData['gamers_info'][$x]);
		break;
	}
}
$engine->rowUpdate(['gamers'=>implode(',',$EveningData['gamers']),'gamers_info'=>json_encode($EveningData['gamers_info'],JSON_UNESCAPED_UNICODE)],['id'=>$EveningData['id']],SQL_TBLEVEN);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));