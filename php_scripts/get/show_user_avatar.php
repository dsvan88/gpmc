<?php 
$result = array(
	'error'	=> 0,
	'size'	=> '500,500',
	'html'	=> '',
	'txt'	=> ''
);
$max_height = 450;
$user = $engine->GetGamerData(['avatar'],['id'=>$_POST['u']],1);
if ($user['avatar'] === '')
{
	$result['error'] = 1;
	$result['txt'] = 'Пользователь, пока не установил себе аватар!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$path = FILE_USRGALL.$_POST['u'].'/';
$original = 'originals/'.str_replace('_3,5x4','',$user['avatar']);
$user['avatar'] = file_exists($root_path.$path.$original) ? $path.$original : $path.$user['avatar'];
if (!file_exists($root_path.$user['avatar']))
{
	$result['error'] = 1;
	$result['txt'] = 'Не найден на сервере! Возможно, он был удалён.';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$a = getimagesize($root_path.$user['avatar']);
$size=$a[0].'x'.$a[1];
$ratio = $a[0]/$a[1];
if ($a[1] > $max_height)
{
	$a[0] = $max_height*$ratio;
	$a[1] = $max_height;
}
$result['html'] = '<div class="ImgPlace" style="width:100%;height:100%"><img src="'.$user['avatar'].'" style="width:100%;height:100%"></div>';
$result['size'] = ($a[0]+40).','.$a[1];
exit(json_encode($result,JSON_UNESCAPED_UNICODE));