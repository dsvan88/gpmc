<?
$result = array(
	'error'	=> 0,
	'html'	=> '',
	'txt'	=> ''
);
$result['html'] = '<iframe src="js/kcfinder/browse.php?type=images" style="width:800px;height:500px"></iframe>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));
