<?php
$result = array(
	'error'=> 0,
	'txt' => 'Успешно сохранено!',
	'wrong' => ''
);
// error_log($_POST['value'].' '.$_POST['name'].' '.$_POST['id']);
$value = preg_replace('#http(s|)\:\/\/'.$_SERVER['SERVER_NAME'].'#i','',isset($_POST['html']) ? $_POST['html'] : $_POST['value']);
$engine->SetSettings(array('id'=>$_POST['id'],'name'=>$_POST['name'],'value'=>$value));
exit(json_encode($result,JSON_UNESCAPED_UNICODE));