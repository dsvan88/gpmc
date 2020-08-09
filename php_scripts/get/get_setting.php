<?
$need = str_replace('get_setting_','',$need);
$setting = $engine->GetSettings(array('shname','name','value'),$need,array('id'=>$_POST['id']))[0];
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
if ($need === 'img')
{
	$max_height = 350;
	$a = getimagesize($root_path.'/'.$setting['value']);
	$size=$a[0].'x'.$a[1];
	if ($a[1] > $max_height)
	{
		$ratio = $a[0]/$a[1];
		$a[0] = $max_height*$ratio;
		$a[1] = $max_height;
	}
	$result['html'] = '<form id="EditSettingImg">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<div class="input_row"><span>Коротка назва:</span><span>'.$setting['shname'].'</span></div>
	<div class="input_row"><span>Опис:</span><input type="text" name="name" value="'.$setting['name'].'"></div>
	<div class="ImgPlace"style="width:'.$a[0].'px;height:'.$a[1].'px;"><img alt="'.$setting['name'].'" title="'.$setting['name'].'" src="'.$setting['value'].'" style="width:'.$a[0].'px;height:'.$a[1].'px"></div>
	<div style="text-align:center"><span class="span_button" id="SaveSettingData"><img src="'.$settings['img']['apply']['value'].'"/>Сохранить<img src="'.$settings['img']['apply']['value'].'"/></span></div>
	</form>
	<br>
	<span class="info_span">Розмір: '.$size.'*<br>*Для кооректности изображения - новые изображения желательно подгонять как можно ближе к размерам предыдущего</span>
	<script>
	var callBackReady = true;
	</script>';
	$result['size'] = ($a[0]+40).','.($a[1]+200);
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
elseif ($need === 'txt')
{
	$result['html'] = '<form id="EditSettingTxt" method="POST" action="switcher.php?need=get_setting_txt">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<div class="input_row"><span>Коротка назва:</span><span>'.$setting['shname'].'</span></div>
	<div class="input_row"><span>Опис:</span><input type="text" name="name" value="'.$setting['name'].'"></div>
	<textarea id="editor" name="html">'.str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$setting['value']).'</textarea><br>
	<form>
	</span class="info_span"></span>';
	$result['size'] = '700,500';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}