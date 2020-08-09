<?
$_POST['i'] = (int) $_POST['i'];
$players = str_replace('»','"',$_POST['p']);
if ($need === 'save_game')
{
	$vars = json_decode(str_replace('»','"',$_POST['v']),true);
	$_POST['w'] = (int)$_POST['w'];
	if ($_POST['w'] > 0)
	{
		$p = json_encode($engine->CalculatePoints(json_decode($players,true),json_decode(str_replace('»','"',$_POST['v']),true)),JSON_UNESCAPED_UNICODE);
		$_SESSION['id_game'] = -1;
		echo $p;
	}
	elseif (!isset($_SESSION['id_game']) && $_POST['w'] === 0) $_SESSION['id_game'] = $_POST['i'];
	$engine->UpdateRow(array('win'=>$_POST['w'],'players'=>str_replace('"','»',$players),'vars'=>$_POST['v'],'txt'=>$_POST['text']),array('id'=>$_POST['i']));
}
else
	$engine->RecordLogFile($_POST['i'],trim($_POST['log']));