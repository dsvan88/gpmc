<?
$userId = $_SESSION['id'];
if ($_POST['uid'] !== $_SESSION['id']){

	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
	$users = new Users;

	if ($_SESSION['status'] === 'admin' && $users->checkToken())
		$userId = $_POST['uid'];
	else{
		$output['error'] = 1;
		$output['html'] = 'Ви не можете змінувати інформацію інших користувачів!';
		exit(json_encode($output,JSON_UNESCAPED_UNICODE));
	}
}

$gd_data = json_decode($_POST['data']);
$_POST['image'] = substr($_POST['image'],strrpos($_POST['image'],'/')+1);

$path = $_SERVER['DOCUMENT_ROOT'].FILE_USRGALL.$userId.'/originals';

if (!file_exists($path))
{
	error_log('Cann’t find folders: '.$path);
	exit('Cann’t find folders: '.$path);
}

$originalFile = $path.'/'.$_POST['image'];
$func = str_replace('image/','',mime_content_type($originalFile));
$src = ('imagecreatefrom'.$func)($originalFile);

// Повернуть изображение в соответствии с его ориентацией
$exifOriginal = exif_read_data($originalFile);

$src = imagerotate($src,-($exifOriginal['Orientation']*90.0-90.0),0);

$src = imagecrop($src, ['x' => $gd_data->x, 'y' => $gd_data->y, 'width' => $gd_data->width, 'height' => $gd_data->height]);
$new_name = substr($_POST['image'],0,strrpos($_POST['image'],'.')).'_3,5x4.'.$func;
if ($src !== FALSE)
{
	$max_height = 400;
	if ($gd_data->height > $max_height)
	{
		$ratio = $gd_data->width/$gd_data->height;
		$src = imagescale($src, $max_height*$ratio, $max_height);
	}
	$pathCropped = FILE_USRGALL.$userId.'/'.substr($new_name,0,strrpos($new_name,'.')+1);
	$fullPath = $_SERVER['DOCUMENT_ROOT'].$pathCropped;
	('image'.$func)($src, $fullPath.$func);
	imagewebp($src,$fullPath.'webp');
	
	imagedestroy($src);
/* 
	print_r(exif_read_data($originalFile));
	echo "\r\n";
	print_r(exif_read_data($_SERVER['DOCUMENT_ROOT'].$pathCropped.$func)); */

	$CommonActionObject->rowUpdate(array('avatar'=>$new_name),array('id'=>$userId),SQL_TBLUSERS);
	$output['error']=0;
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.image-processing.php';

	$images = new ImageProcessing;

	$output['text'] ='Успішно!';
	$output['html'] = $images->inputImage($pathCropped.$func,['title'=>'Аватар користувача']);
}
else 
{
	$output['error']=1;
	$output['html']='Что-то пошло не так!';
}