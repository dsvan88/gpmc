<? 
if (!defined('JSFUNC_LOAD'))
{
	include 'dir_cfg.php';
	$engine_set = 'JSFUNC';
	include $root_path.'/engine/engine.php'; 
	$engine = new JSFunc();
}
$players = $engine->GetPlayersIDs($engine->SetPlayersDefaults($_POST));
$ids = $players['ids'];
unset($players['ids']);
$engine->StartGame($_POST['e'],$ids,$players,$_POST['manager']);
$engine->RecordLogFile($_SESSION['id_game'],date('d.m.Y H:i').': Игра успешно начата!');
echo $_SESSION['id_game'];