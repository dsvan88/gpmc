<?php
$img = $engine->settingsGet(array('shname','name','value'), 'img',array('id'=>$_POST['editTarget']))[0];

[$x, $y] = getimagesize($root_path.'/'.$img['value']);
$imgStyle = 'width:100%;height:auto';
if ($y > 300)
	$imgStyle = 'height:100%;width:auto';

$output['html'] .= '
<form class="edit-settings-image-form">
<h2 class="title">Зміна налаштувань: Зображення</h2>
<input type="hidden" name="id" value="'.$_POST['editTarget'].'">
<div class="input_row">
	<label>Коротка назва:</label>
	<span>'.$img['shname'].'</span>
</div>
<div class="input_row">
	<label>Опис:</label>
	<input type="text" name="name" value="'.$img['name'].'">
</div>
<div class="image-place" style="width:'.($x > 400 ? 400 : $x).'px;height:'.($y > 300 ? 300: $y).'px;" >
	<a href="../js/kcfinder/browse.php?type=images" target="_blank" data-form-type="get-kcfinder-browser">
		<img alt="'.$img['name'].'" title="'.$img['name'].'" src="'.$img['value'].'" style="'.$imgStyle.'">
	</a>
</div>
<div class ="input_row buttons">
	<button>Сохранить</button>
</div>
<form>
<span class="info_span">Розмір: '.$x.'x'.$y.'*<br>*Следите за соотношением сторон!</span>';
$output['javascript'] = 'callBackReady = true';