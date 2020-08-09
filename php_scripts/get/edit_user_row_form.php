<?
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
$user = $engine->GetPlayerData(['id','name','fio','rank','status','birthday','gender','email','ar'],['id'=>$_POST['id']],1);
$genders=array('-','господин','госпожа','некто');
$statuses = array('Гость', 'Резидент', 'Основатель');
$ranks = array('C', 'B', 'A');
$result['html'] = '<td>'.$user['id'].'. </td>
	<td><input type="text" name="name" value="'.$user['name'].'" size="8"/></td>
	<td><input type="text" name="fio" value="'.$user['fio'].'" size="8"/></td>
	<td><select name="status">';
	for($x=0;$x<count($statuses);$x++)
		$result['html'] .= '<option value="'.$x.'"'.($user['status'] == $x ? ' selected ' : '').'>'.$statuses[$x].'</option>';
	$result['html'] .= '</td>
	<td><select name="rank">';
	for($x=0;$x<count($ranks);$x++)
		$result['html'] .= '<option value="'.$x.'"'.($user['rank'] == $x ? ' selected ' : '').'>'.$ranks[$x].'</option>';
	$result['html'] .= '</td>
	<td><input type="text" name="birthday" value="'.($user['birthday'] > 0 ? date('d.m.Y',$user['birthday']) : '').'" size="8"/></td>
	<td><select name="gender">';
	for($x=0;$x<count($genders);$x++)
		$result['html'] .= '<option value="'.$x.'"'.($user['gender'] == $x ? ' selected ' : '').'>'.$genders[$x].'</option>';
	$result['html'] .= '</td>
	<td></td>
	<td><input type="text" name="email" value="'.$user['email'].'" size="15"/></td>
	<td><input type="checkbox" name="ar" value="1"'.($user['ar'] > 0 ? ' checked disabled' : '').'/></td>
	<td></td>
	<td><a class="ApplyTA" title="Принять" alt="Принять"><img src = "'.$settings['img']['apply']['value'].'"/></a></td>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));