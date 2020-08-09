<?php
$result = array(
	'error'=> 0,
	'txt' => 'Успешно сохранено!',
	'wrong' => ''
);
$value = preg_replace('#http(s|)\:\/\/'.$_SERVER['SERVER_NAME'].'#i','',isset($_POST['html']) ? $_POST['html'] : $_POST['u']);
$engine->SetSettings(array('id'=>$_POST['id'],'name'=>$_POST['n'],'value'=>$value));
exit(json_encode($result,JSON_UNESCAPED_UNICODE));
/*if (!isset($_POST['t']))
{
	if (!preg_match('/^[12]{1}[17-90]{1}\:[0-5]{1}[05]{1}$/',$_POST['plan_arrive'],$r) || $r[0] !== $_POST['plan_arrive'] || strtotime(date('d.m.Y',$EveningData['date']).' '.$_POST['plan_arrive']) < $EveningData['date'])
	{
		$result['error'] = 1;
		$result['txt'] = 'Не верный формат времени!'.PHP_EOL.'"ЧАСЫ:МИНУТЫ" начиная с '.date('H:i',$EveningData['date']).' (шаг в 5 минут)';
		$result['wrong'] = 'plan_arrive';
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}
	$engine->UpdateRow(array('players'=> $EveningData['players'].','.$_SESSION['id'],'times'=> $EveningData['times'].','.$_POST['plan_arrive'],'tobe'=> $EveningData['tobe'].','.$_POST['plan_tobe']),array('id'=>$EveningData['id']),MYSQL_TBLEVEN);
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$EveningData['players'] = explode(',',$EveningData['players']);
	$EveningData['times'] = explode(',',$EveningData['times']);
	$EveningData['tobe'] = explode(',',$EveningData['tobe']);
	error_log(implode(',',$EveningData['players']));
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
	$result['txt'] = 'Очень жаль!'.PHP_EOL.'Надеемся увидеть Вас в следующий раз!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}*/