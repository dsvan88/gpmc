<?php 

$user = $engine->GetGamerData(['avatar','name'],['id'=>$_POST['userId']],1);
$user['avatar'] = FILE_USRGALL.$_POST['userId'].'/'.$user['avatar'];
$output['html'] .= '
<form>
	<h2>Аватар пользователя '.$user['name'].'</h2>
	<div class="input_row big-avatar">
		<img src="'.$user['avatar'].'">
	</div>';

if ($_SESSION['id'] == $_POST['userId'])
	$output['html'] .= '
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
	</div>';

$output['html'] .= '
</form>';