<?
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
$point = $engine->settingsGet(array('id','name','value'),'point',array('id'=>$_POST['id']))[0];
$result['html'] = '
	<span>
		<input type="text" name="name" value="'.$point['name'].'">
	</span>
	<span>
		<input type="text" name="value" value="'.$point['value'].'">
	</span>
	<a title="Принять" alt="Принять" data-action-type="apply-new-points">'.
		$engine->inputImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']])
	.'</a>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));