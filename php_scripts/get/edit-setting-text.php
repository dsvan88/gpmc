<?
$setting = $engine->settingsGet(array('shname','name','value'),'txt',array('id'=>$_POST['id']))[0];
$result = array(
	'error'	=> 0,
	'html'	=> ''
);
$result['html'] = '
<form method="POST" action="switcher.php?need=get_setting_txt">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<div class="input_row">
		<label>Коротка назва:</label>
		<span>'.$setting['shname'].'</span>
	</div>
	<div class="input_row">
		<label>Опис:</label>
		<input type="text" name="name" value="'.$setting['name'].'">
	</div>
	<textarea name="html">
		'.str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$setting['value']).'
	</textarea>
<form>';
exit(json_encode($result,JSON_UNESCAPED_UNICODE));
