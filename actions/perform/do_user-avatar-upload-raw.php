<?
/* if (isset($_GET['CKEditorFuncNum']))
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
 */

if(isset($_FILES['img']['tmp_name']))
{	
	$path = $_SERVER['DOCUMENT_ROOT'].FILE_USRGALL.$_SESSION['id'].'/originals/';
	if (!file_exists($path))
		mkdir($path, 0777, true);
	if (!file_exists($path)) exit('Cann’t create folders: '.$path);
	$newName = md5_file($_FILES['img']['tmp_name']).'.'.str_replace('image/','',mime_content_type($_FILES['img']['tmp_name']));
	move_uploaded_file($_FILES['img']['tmp_name'], $path.$newName);
	// error_log($path.$newName);
}
else
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
    $users = new Users();

	$path = FILE_USRGALL.$_SESSION['id'].'/originals/';
	$user = $users->usersGetData(['avatar'],['id'=>$_SESSION['id']],1);
	$newName = str_replace('_3,5x4','',$user['avatar']);
	if (!file_exists($_SERVER['DOCUMENT_ROOT'].$path.$newName))
	{
		$output['error'] = 1;
		$output['text'] = 'Не найден на сервере! Возможно, он был удалён.';
		exit(json_encode($output,JSON_UNESCAPED_UNICODE));
	}	
}
$fullPath = FILE_USRGALL.$_SESSION['id'].'/originals/'.$newName;
$max_height = 400;
[$x, $y] = getimagesize($_SERVER['DOCUMENT_ROOT'].$fullPath);
$size=$x.'x'.$y;
if ($y > $max_height)
{
	$ratio = $x/$y;
	$x = $max_height*$ratio;
	$y = $max_height;
}
$replace['{IMAGE_STYLE}'] = "style='width:{$x}px;height:{$y}px'";
$replace['{IMAGE_FULLPATH}'] = $fullPath;
$replace['{IMAGE_FILENAME}'] = $newName;
$output['html'] = str_replace(array_keys($replace),array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_user-crop-avatar.html'));