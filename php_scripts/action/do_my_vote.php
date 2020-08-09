<?php
$engine_set = 'VOTES';
include $root_path.'/engine/engine.php'; 
$result=array(
	'error' => 0,
	'txt' => ''
);
$_POST['m'] = (int) $_POST['m'];
$_POST['v'] = (int) $_POST['v'];
$_POST['p'] = (int) $_POST['p'];
$_POST['t'] = (int) $_POST['t'];

if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут голосовать за изменение '.($_POST['t']===0 ? 'категории' : 'статуса').' игроков!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine = new VoteSystem();
$v_data = $engine->GetVoteData(array('id','object','type','open'),array('id'=>$_POST['v']));
if ($_SESSION['id'] === $v_data['object'])
{
	$result['error'] = 1;
	$result['txt'] = 'Вы не можете голосовать за изменение '.($_POST['t']===0 ? 'своей категории' : 'своего статуса').'!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$motion = array('Против повышения категории','За повышение категории','Против повышения статуса','За повышение статуса');
$types = array('Изменение категории','Изменение статуса');
if ($v_data['id'] > 0)
{
	$c = $engine->CheckUserVotes($_SESSION['id'],$r);
	if ($c > 0)
	{
		$result['error'] = 1;
		$result['txt'] = "Вы уже проголосовали по этому голосованию!\r\nПовторное голосование возможно только по завершению текущего!";
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}
	$player_name = $engine->GetPlayerName($v_data['object']);
	$txt = $types[$v_data['type']].' игрока '.$player_name;
	$result['error'] = 0;
	$result['txt'] = "Вы успешно проголосовали:\r\n$txt!";
	$engine->AddVoteEvent(array('object'=>$v_data['id'],'type'=>$_POST['m']+10,'name'=>$txt,'txt'=>$_POST['html'],'author'=>$_SESSION['id']));
	$c = $engine->CheckVoteGoal($v_data['id']);
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$player_name = $engine->GetPlayerName($_POST['p']);
	$txt = $types[$_POST['t']].' игрока '.$player_name;
	$result['error'] = 0;
	$result['txt'] = "Вы успешно создали голосование:\r\n$txt!";
	$v_data['id'] = $engine->AddVoteEvent(array('object'=>$_POST['p'],'type'=>$_POST['t'],'name'=>$txt,'author'=>$_SESSION['id']));
	$engine->AddVoteEvent(array('object'=>$v_data['id'],'type'=>$_POST['m']+10,'name'=>$txt,'txt'=>$_POST['html'],'author'=>$_SESSION['id']));
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}