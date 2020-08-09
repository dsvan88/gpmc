<?
$result=array(
	'error' => 0,
	'html'=>''
);

$gd_data = json_decode(str_replace('»','"',$_POST['d']));

$path = $root_path.FILE_USRGALL.$_SESSION['id'].'/originals';
if (!file_exists($path)) exit('Cann’t find folders: '.$path);
$func = strpos($_POST['i'],'.png') !== false ? 'png' : 'jpeg';
$src = ('imagecreatefrom'.$func)($path.'/'.$_POST['i']);
$src = imagecrop($src, ['x' => $gd_data->x, 'y' => $gd_data->y, 'width' => $gd_data->width, 'height' => $gd_data->height]);
$new_name = substr($_POST['i'],0,strrpos($_POST['i'],'.')).'_3,5x4'. substr($_POST['i'],strrpos($_POST['i'],'.'));
if ($src !== FALSE)
{
	$max_height = 400;
	if ($gd_data->height > $max_height)
	{
		$ratio = $gd_data->width/$gd_data->height;
		$src = imagescale($src, $max_height*$ratio, $max_height);
	}
    ('image'.$func)($src, $root_path.FILE_USRGALL.$_SESSION['id'].'/'.$new_name,100);
	imagedestroy($src);
	$engine->UpdateRow(array('avatar'=>$new_name),array('id'=>$_SESSION['id']),MYSQL_TBLPLAYERS);
	$result['error']=0;
	$result['html']='Успешно!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else 
{
	imagedestroy($src);
	$result['error']=1;
	$result['html']='Что-то пошло не так!';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}