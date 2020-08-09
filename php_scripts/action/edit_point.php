<?php
$result=array(
	'error' => 0,
	'txt' => '',
	'html' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут редактировать рейтинговые баллы';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$data = ['id'=>'','name'=>'','value'=>'0.0'];
foreach($data as $k=>$v)
{
	if (!isset($_POST[$k])) continue;
	$data[$k] = trim($k === 'value' ? str_replace(' ','',$_POST[$k]) : $_POST[$k]);
}
$engine->SetSettings($data);
$result['txt'] = 'Успешно изменено!';
$result['html'] = '<span class="point_name"><b>'.$data['name'].'</b></span><span class="point_value">'.str_replace(',',', ',$data['value']).'</span><a class="EditPencil"><img src = "'.$settings['img']['edit_pen']['value'].'" title="'.$settings['img']['edit_pen']['name'].'" alt="'.$settings['img']['edit_pen']['value'].'"/></a>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));