<?php
if (!defined('GETS_LOAD'))
{
	$engine_set = 'GETS';
	include $root_path.'/engine/engine.php'; 
	$engine = new GetDatas();
}
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
$result['html'] = $engine->getVotingListHTML($_POST['type'] === 'active' ? 1 : 0);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));