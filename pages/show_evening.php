<form class="evening-booking" id="eveningRegisterForm" method="POST">
<?
	$days = array( 'Воскресенье', 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота', 'Воскресенье');
	$plan_tobe = array('','1-2 игры', '2-3 игры', '3-4 игры');

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
	
	if (isset($_SESSION['status']) && $_SESSION['status'] > 1)
		require $root_path.'/evening/active_resident.php';
	else require $root_path.'/templates/list_for_'.$user['status'].'.php';
?>