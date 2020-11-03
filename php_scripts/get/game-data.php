<?
$result = array(
	'error'	=> 0,
	'txt'	=> ''
);
$result['txt'] = json_encode($engine->ResumeGame($_GET['gid']),JSON_UNESCAPED_UNICODE);
error_log(json_encode($result));
exit(json_encode($result));