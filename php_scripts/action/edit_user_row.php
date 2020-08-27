<?php
$result=array(
	'error' => 0,
	'txt' => '',
	'html' => ''
);
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['error'] = 1;
	$result['txt'] = 'Не авторизованные пользователи не могут редактировать данные пользователей';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$data = ['name'=>'','fio'=>'','rank'=>'','status'=>'','birthday'=>0,'gender'=>'','email'=>'','ar'=>''];
foreach($data as $k=>$v)
{
	if (!isset($_POST[$k])) continue;
	if ($k !== 'birthday') $data[$k] = $_POST[$k];
	else $data[$k] = $_POST[$k] !== '' ? strtotime($_POST[$k]) : 0;
}
$engine->UpdateRow($data,array('id'=>$_POST['id']),MYSQL_TBLGAMERS);
$result['txt'] = 'Успешно изменено!';
$user = $engine->GetGamerData(array('last_game','username'),['id'=>$_POST['id']],1);
$genders=array('-','господин','госпожа','некто');
$statuses = array('', 'Резидент', 'Основатель');
$cats = array('C', 'B', 'A');
$result['html'] = '<td>'.$_POST['id'].'</td>
	<td>'.$data['name'].'</td>
	<td>'.$data['fio'].'</td>
	<td>'.$statuses[$data['status']].'</td>
	<td>'.$cats[$data['rank']].'</td>
	<td>'.($data['birthday'] > 0 ? $_POST['birthday'] : '').'</td>
	<td>'.$genders[$data['gender']].'</td>
	<td>'.($user['username'] != '' ? '+' : '').'</td>
	<td>'.$data['email'].'</td>
	<td>'.($data['ar'] > 0 ? '+' : '').'</td>
	<td>'.$user['last_game'].'</td>
	<td><a class="EditPencil"><img src = "'.$settings['img']['edit_pen']['value'].'" title="'.$settings['img']['edit_pen']['name'].'" alt="'.$settings['img']['edit_pen']['value'].'"/></a></td>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));