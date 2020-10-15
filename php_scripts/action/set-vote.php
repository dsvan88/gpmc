<?php
$engine_set = 'VOTES';
include $root_path.'/engine/engine.php'; 
$result=array(
	'error' => 0,
	'txt' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут голосовать за изменение '.($_POST['type']==='rank' ? 'категории' : 'статуса').' игроков!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine = new VoteSystem();
$v_data = $engine->GetVoteData(array('id','object','type','open'),array('id'=>(int)$_POST['voteid']));
if ($_SESSION['id'] === $v_data['object'])
{
	$result['error'] = 1;
	$result['txt'] = 'Вы не можете голосовать за изменение '.($_POST['type']==='rank' ? 'своей категории' : 'своего статуса').'!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$types = array('rank'=>'Изменение <b>категории</b>','status'=>'Изменение <b>статуса</b>');
if ($v_data['id'] > 0)
{
	$check = $engine->CheckUserVotes($_SESSION['id'],$r);
	if ($check > 0)
	{
		$result['error'] = 1;
		$result['txt'] = "Вы уже проголосовали по этому голосованию!\r\nПовторное голосование возможно только по завершению текущего!";
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}
	$player_name = $engine->GetGamerName($v_data['object']);
	$txt = $types[$v_data['type']].' игрока '.$player_name;
	$result['error'] = 0;
	$result['txt'] = "Вы успешно проголосовали:\r\n$txt!";
	$engine->AddVoteEvent(array('object'=>$v_data['id'],'type'=>$_POST['motion'],'name'=>$txt,'txt'=>$_POST['html'],'author'=>$_SESSION['id']));
	$c = $engine->CheckVoteGoal($v_data['id']);
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$player_name = $engine->GetGamerName($_POST['player_id']);
	$txt = $types[$_POST['type']].' игрока '.$player_name;
	$result['error'] = 0;
	$result['txt'] = "Вы успешно создали голосование:\r\n$txt!";
	$v_data['id'] = $engine->AddVoteEvent(array('object'=>$_POST['player_id'],'type'=>$_POST['type'],'name'=>$txt,'author'=>$_SESSION['id']));
	$engine->AddVoteEvent(array('object'=>$v_data['id'],'type'=>$_POST['motion'],'name'=>$txt,'txt'=>$_POST['html'],'author'=>$_SESSION['id']));
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}