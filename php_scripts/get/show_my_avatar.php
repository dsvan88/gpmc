<?php 
$result = array(
	'error'	=> 0,
	'size'	=> '500,500',
	'html'	=> '',
	'txt'	=> ''
);
$max_height = 450;
$user = $engine->GetPlayerData(['avatar'],['id'=>$_SESSION['id']],1);
$user['avatar'] = FILE_USRGALL.$_SESSION['id'].'/'.$user['avatar'];
$a = getimagesize($root_path.$user['avatar']);
$size=$a[0].'x'.$a[1];
if ($a[1] > $max_height)
{
	$ratio = $a[0]/$a[1];
	$a[0] = $max_height*$ratio;
	$a[1] = $max_height;
}
$result['html'] = '<div class="ImgPlace" style="width:100%;height:90%"><img src="'.$user['avatar'].'" style="width:100%;height:100%"></div>
<div class="span_buttons_place">&nbsp;&nbsp;&nbsp;&nbsp;
	<span class="span_button" id="ReCropMyAvatar"><img src="'.$settings['img']['edit_pen']['value'].'"/>Переобрезать<img src="'.$settings['img']['edit_pen']['value'].'"/></span>&nbsp;&nbsp;&nbsp;&nbsp;
	<span class="span_button" id="CropMyNewAvatar">&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$settings['img']['edit_pen']['value'].'"/>Новый<img src="'.$settings['img']['edit_pen']['value'].'"/>&nbsp;&nbsp;&nbsp;&nbsp;</span>
</div>';
$result['size'] = ($a[0]+40).','.$a[1];
exit(json_encode($result,JSON_UNESCAPED_UNICODE));