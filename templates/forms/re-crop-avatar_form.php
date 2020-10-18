<?
if (isset($_GET['CKEditorFuncNum']))
{
	if (!file_exists($root_path.FILE_MAINGALL))
		mkdir($root_path.FILE_MAINGALL, 0777, true);
	if (!file_exists($root_path.FILE_MAINGALL)) exit('Cann’t create folders: '.$root_path.FILE_MAINGALL);
	$path .= $_FILES['upload']['name'];
	if(!move_uploaded_file($_FILES['upload']['tmp_name'], $root_path.FILE_MAINGALL)) {
		error_log('Some error occured please try again later');
		exit();
	}
	exit('<script>window.parent.CKEDITOR.tools.callFunction('.$_GET['CKEditorFuncNum'].', "'.$path.'", "")</script>');
}

if(isset($_FILES['img']['tmp_name']))
{	
	$path = $root_path.FILE_USRGALL.$_SESSION['id'].'/originals/';
	if (!file_exists($path))
		mkdir($path, 0777, true);
	if (!file_exists($path)) exit('Cann’t create folders: '.$path);
	$new_name = md5_file($_FILES['img']['tmp_name']).substr($_FILES['img']['name'],strrpos($_FILES['img']['name'],'.'));
	move_uploaded_file($_FILES['img']['tmp_name'], $path.$new_name);
}
else
{
	$path = FILE_USRGALL.$_SESSION['id'].'/originals/';
	$user = $engine->GetGamerData(['avatar'],['id'=>$_SESSION['id']],1);
	$new_name = str_replace('_3,5x4','',$user['avatar']);
	if (!file_exists($root_path.$path.$new_name))
	{
		$output['error'] = 1;
		$output['txt'] = 'Не найден на сервере! Возможно, он был удалён.';
		exit(json_encode($output,JSON_UNESCAPED_UNICODE));
	}	
}
$full_path = FILE_USRGALL.$_SESSION['id'].'/originals/'.$new_name;
$output['html'] = '
	<div class="ImgPlace" style="width:100%;height:85%">
		<img class="original_img" id="img_for_crop" src='.$full_path.' style="width:100%;height:100%">
	</div>
	<br />
	<input type="hidden" name="filename" value="'.$new_name.'">
	<div class="vote_button_row"><span class="span_button" id="CropMyAvatar"><img src="'.$settings['img']['apply']['value'].'"/>Применить<img src="'.$settings['img']['apply']['value'].'"/></span></div>';
$max_height = 400;
$a = getimagesize($root_path.$full_path);
$size=$a[0].'x'.$a[1];
if ($a[1] > $max_height)
{
	$ratio = $a[0]/$a[1];
	$a[0] = $max_height*$ratio;
	$a[1] = $max_height;
}
$output['size'] = ($a[0]+140).','.($a[1]+30);
exit(json_encode($output,JSON_UNESCAPED_UNICODE));