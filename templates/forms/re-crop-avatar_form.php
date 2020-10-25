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
	$new_name = md5_file($_FILES['img']['tmp_name']).'.'.str_replace('image/','',mime_content_type($_FILES['img']['tmp_name']));
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
$max_height = 400;
[$x, $y] = getimagesize($root_path.$full_path);
$size=$x.'x'.$y;
if ($y > $max_height)
{
	$ratio = $x/$y;
	$x = $max_height*$ratio;
	$y = $max_height;
}
$output['html'] = '
	<form>
		<h2>Налаштування аватара</h2>
		<div class="input_row cropped-image-place" style="width:'.$x.'px;height:'.$y.'px">
			<img src="'.$full_path.'" style="width:'.$x.'px;height:'.$y.'px">
		</div>
		<br />
		<input type="hidden" name="filename" value="'.$new_name.'">
		<div class="input_row buttons">
			<button>
					'
					.$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
					'
					Применить
					'
					.$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
					'
				</button>
		</div>
	</form>';