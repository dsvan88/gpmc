<?php 
if ($_SESSION['id'] == $_POST['userId'])
{
	$max_height = 450;
	$user = $engine->GetGamerData(['avatar'],['id'=>$_SESSION['id']],1);
	$user['avatar'] = FILE_USRGALL.$_SESSION['id'].'/'.$user['avatar'];
	[$x, $y] = getimagesize($root_path.$user['avatar']);
	$size=$x.'x'.$y;
	if ($y > $max_height)
	{
		$ratio = $x/$y;
		$x = $max_height*$ratio;
		$y = $max_height;
	}
	// $output['html'] .= '
	// <form>
	// 	<h2>Аватар пользователя '.$engine->GetGamerName($_SESSION['id']).'</h2>
	// 	<div class="input_row" style="width:100%;height:90%">
	// 		<img src="'.$user['avatar'].'" style="width:100%;height:100%">
	// 	</div>
	// 	<div class="input_row">
	// 		<span class="span_button" id="ReCropMyAvatar">
	// 			<img src="'.$settings['img']['edit_pen']['value'].'"/>
	// 			Переобрезать
	// 			<img src="'.$settings['img']['edit_pen']['value'].'"/>
	// 		</span>
	// 		<span class="span_button" id="CropMyNewAvatar">
	// 			<img src="'.$settings['img']['edit_pen']['value'].'"/>
	// 			Новый
	// 			<img src="'.$settings['img']['edit_pen']['value'].'"/>
	// 		</span>
	// 	</div>
	// </form>';
	$output['html'] .= '
	<form>
		<h2>Аватар пользователя '.$engine->GetGamerName($_SESSION['id']).'</h2>
		<div class="input_row big-avatar">
			<img src="'.$user['avatar'].'">
		</div>
		<div class="input_row">
			<span class="span_button" data-form-type="re-crop-avatar">
				'.$engine->checkAndPutImage($settings['img']['edit_pen']['value']).'
				Переобрезать
				'.$engine->checkAndPutImage($settings['img']['edit_pen']['value']).'
			</span>
			<span class="span_button" data-action-type="crop-new-avatar">
				'.$engine->checkAndPutImage($settings['img']['edit_pen']['value']).'
				Новый
				'.$engine->checkAndPutImage($settings['img']['edit_pen']['value']).'
			</span>
		</div>
	</form>';
}