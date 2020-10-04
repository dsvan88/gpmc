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
[$x, $y] = getimagesize($root_path.'/'.$img['value']);
$size=$x.'x'.$y;
$x = $x > 400 ? 400 : $x;
$y = $y > 300 ? 300 : $y;
$result['html'] = '<form id="EditSettingImg">
<input type="hidden" name="i_id" value="'.$_POST['id'].'">
<div class="input_row"><span>Коротка назва:</span><span>'.$img['shname'].'</span></div>
<div class="input_row"><span>Опис:</span><input type="text" name="name" value="'.$img['name'].'"></div>
<div class="ImgPlace"style="width:'.$x.'px;height:'.$y.'px;"><a href="../js/kcfinder/browse.php?type=images" target="_blank"><img alt="'.$img['name'].'" title="'.$img['name'].'" src="'.$img['value'].'" style="width:'.$x.'px;height:'.$y.'px"></a></div>
<div style="text-align:center"><span class="span_button" id="SaveSettingData"><img src="'.$settings['img']['apply']['value'].'"/>Сохранить<img src="'.$settings['img']['apply']['value'].'"/></span></div>
<form>
<br>
<span class="info_span">Розмір: '.$size.'*<br>*Для кооректности изображения - новые изображения желательно подгонять как можно ближе к размерам предыдущего</span>
<script>
var callBackReady = true;
</script>';
$result['size'] = ($x+40).','.($y+180);
exit(json_encode($result,JSON_UNESCAPED_UNICODE));