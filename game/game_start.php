<? 
if (!defined('JSFUNC_LOAD'))
{
	$engine_set = 'JSFUNC';
	include $root_path.'/engine/engine.php'; 
	$engine = new JSFunc();
}
$players = $engine->GetGamersIDs($engine->SetPlayersDefaults($_POST));
$ids = $players['ids'];
unset($players['ids']);
$engine->StartGame($_POST['e'],$ids,$players,$_POST['manager']);
$engine->RecordLogFile($_SESSION['id_game'],date('d.m.Y H:i').': Игра успешно начата!');
echo $_SESSION['id_game'];