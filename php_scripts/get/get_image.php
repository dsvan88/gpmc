<?php
$engine_set = 'GETS';
include $root_path.'/engine/engine.php'; 
$engine = new GetDatas();
$img = $engine->GetSettings(array('shname','name','value'), 'img',array('id'=>$_POST['id']))[0];
$result = array(
	'error'	=> 0,
	'size'	=> '500,500',
	'html'	=> ''
);
$a = getimagesize($root_path.'/'.$img['value']);
$size=$a[0].'x'.$a[1];
$a[0] = $a[0] > 400 ? 400 : $a[0];
$a[1] = $a[1] > 300 ? 300 : $a[1];
$result['html'] = '<form id="EditSettingImg">
<input type="hidden" name="i_id" value="'.$_POST['id'].'">
<div class="input_row"><span>Коротка назва:</span><span>'.$img['shname'].'</span></div>
<div class="input_row"><span>Опис:</span><input type="text" name="name" value="'.$img['name'].'"></div>
<div class="ImgPlace"style="width:'.$a[0].'px;height:'.$a[1].'px;"><a href="../js/kcfinder/browse.php?type=images" target="_blank"><img alt="'.$img['name'].'" title="'.$img['name'].'" src="'.$img['value'].'" style="width:'.$a[0].'px;height:'.$a[1].'px"></a></div>
<div style="text-align:center"><span class="span_button" id="SaveSettingData"><img src="'.$settings['img']['apply']['value'].'"/>Сохранить<img src="'.$settings['img']['apply']['value'].'"/></span></div>
<form>
<br>
<span class="info_span">Розмір: '.$size.'*<br>*Для кооректности изображения - новые изображения желательно подгонять как можно ближе к размерам предыдущего</span>
<script>
var callBackReady = true;
</script>';
$result['size'] = ($a[0]+40).','.($a[1]+180);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));