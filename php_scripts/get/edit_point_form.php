<?
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
$point = $engine->GetSettings(array('id','name','value'),'point',array('id'=>$_POST['id']));
$result['html'] = '<span><input type="text" name="name" value="'.$point[0]['name'].'"></span><span><input type="text" name="value" value="'.$point[0]['value'].'"></span><a class="ApplyTA" title="Принять" alt="Принять"><img src = "'.$settings['img']['apply']['value'].'"/></a>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));