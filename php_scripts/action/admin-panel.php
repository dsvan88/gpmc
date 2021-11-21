<?php
$result=array(
	'error' => 0,
	'txt' => ''
);
if (isset($_SESSION['ba'])){
    unset($_SESSION['ba']);
    exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1){
    $result['error'] = 1;
	$result['txt'] = 'Без авторизації адміном не бути! Схаменіться!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$userData = $engine->getGamerData(array('ar'), array('id'=>$_SESSION['id']));
if ($userData['ar'] === '0'){
	$result['error'] = 1;
	$result['txt'] = 'У Вас немає відповідних повноважень!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$_SESSION['ba'] = rand(1,1000);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));