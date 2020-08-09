<?
$EveningData = array( 'date' => strtotime($_POST['eve_date']),
	'place' => $_POST['eve_place'],
	'p_info' => $_POST['eve_place_info'],
);
if (isset($_POST['gamer']))
{
	$EveningData['gamers'] = 1;
	$EveningData['times'] = implode(',',$_POST['g_time']);
	$EveningData['tobe'] = implode(',',$_POST['tobe']);
}
$engine->SetEveningApplied($EveningData);