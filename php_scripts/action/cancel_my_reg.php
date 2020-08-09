<?php
$result = array(
	'error'=> 0,
	'txt' => 'Очень жаль!'.PHP_EOL.'Надеемся увидеть Вас в следующий раз!'
);
$EveningData = $engine->GetNearEveningData(array('id','date','players','times','tobe'));
$EveningData['players'] = explode(',',$EveningData['players']);
$EveningData['times'] = explode(',',$EveningData['times']);
$EveningData['tobe'] = explode(',',$EveningData['tobe']);
for($x=0;$x<count($EveningData['players']);$x++)
{
	if ($EveningData['players'][$x] === $_SESSION['id'])
	{
		unset($EveningData['players'][$x]);
		unset($EveningData['times'][$x]);
		unset($EveningData['tobe'][$x]);
		break;
	}
}
$engine->UpdateRow(array('players'=>implode(',',$EveningData['players']),'times'=>implode(',',$EveningData['times']),'tobe'=>implode(',',$EveningData['tobe'])),array('id'=>$EveningData['id']),MYSQL_TBLEVEN);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));