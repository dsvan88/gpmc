<?
$result = array(
	'error'	=> 0,
	'html'	=> '',
	'size' => '800,500',
	'txt'	=> ''
);
$result['html'] = '<iframe src="js/kcfinder/browse.php?type=images" style="width:'.str_replace(',','px;height:',$result['size']).'px"><iframe>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));
