<?
if (isset($_POST['data']))
	$data['gamers'] = str_replace('»','"',$_POST['data']);
$data['date'] = strtotime($_POST['eve_date']);
$data['place'] = $_POST['eve_place'];
$data['p_info'] = $_POST['eve_place_info'];
$engine->setEveningApproved($data);