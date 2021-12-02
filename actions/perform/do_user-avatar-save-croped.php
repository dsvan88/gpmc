<?
$result=array(
	'error' => 0,
	'html'=>''
);

$gd_data = json_decode($_POST['data']);
$_POST['image'] = substr($_POST['image'],strrpos($_POST['image'],'/')+1);

$path = $_SERVER['DOCUMENT_ROOT'].FILE_USRGALL.$_SESSION['id'].'/originals';

if (!file_exists($path))
{
	error_log('Cann’t find folders: '.$path);
	exit('Cann’t find folders: '.$path);
}

$originalFile = $path.'/'.$_POST['image'];
$func = str_replace('image/','',mime_content_type($originalFile));
$src = ('imagecreatefrom'.$func)($originalFile);
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

	$fullPath = $_SERVER['DOCUMENT_ROOT'].FILE_USRGALL.$_SESSION['id'].'/'.substr($new_name,0,strrpos($new_name,'.')+1);
	('image'.$func)($src, $fullPath.$func);
	imagewebp($src,$fullPath.'webp');
	
	imagedestroy($src);
	
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
    $action = new Action();

	$action->rowUpdate(array('avatar'=>$new_name),array('id'=>$_SESSION['id']),SQL_TBLUSERS);
	$output['error']=0;
	$output['html']='Успешно!';
}
else 
{
	imagedestroy($src);
	$output['error']=1;
	$output['html']='Что-то пошло не так!';
}