<? 
if (!defined('JSFUNC_LOAD'))
{
	$engine_set = 'JSFUNC';
	include $root_path.'/engine/engine.php'; 
	$engine = new JSFunc();
}
// error_log(json_encode($_POST,JSON_UNESCAPED_UNICODE));
$_POST['player'] = explode(',',$_POST['player']);
$_POST['role'] = explode(',',$_POST['role']);
$players = $engine->usersGetIds($engine->SetPlayersDefaults($_POST));

$ids = $players['ids'];
unset($players['ids']);

$engine->StartGame($_POST['evening'],$ids,$players,$_POST['manager']);
$engine->gameLogRecordFile($_SESSION['id_game'],date('d.m.Y H:i').': Игра успешно начата!');
echo $_SESSION['id_game'];