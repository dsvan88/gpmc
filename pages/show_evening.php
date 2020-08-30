<form class="evening-booking" id="eveningRegisterForm" method="POST">
<?
	$days = array( 'Воскресенье', 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота');
	$plan_tobe = array('','на 1-2 игры', 'на 2-3 игры', 'на 3-4 игры');

	if ($EveningData['ready'] !== false) 
	{
		$EveningData['time'] = date('H:i',$EveningData['date']);
		$EveningData['place'] = $EveningData['place'] === '0' ? array('id'=>'0','name'=>'','info'=>'') : $engine->GetPlaceByID($EveningData['place']);
		if ($EveningData['gamers'] !== '')
			$EveningData['gamers'] = $engine->GetGamersNames($EveningData['gamers'],true);
		if ($EveningData['gamers_info'] !== '')
			$EveningData['gamers_info'] = json_decode($EveningData['gamers_info'],true);
	}
	if ($EveningData['ready'] === false)
		$EveningData = array(
			'date'=>strtotime(date('d.m.Y 17:00')),
			'place' => [
				'name'=>'',
				'info' => ''
			]
		);
	if ($EveningData['gamers'] === '')
		$EveningData['gamers'] = $engine->GetResidentsNames(11);

	require $root_path.'/evening/evening_active_'.$user['status'].'.php';
?>