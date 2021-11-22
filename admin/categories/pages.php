<?
	if (isset($_POST['short_name']))
	{
		$engine_set = 'JSFUNC';
		require $root_path.'/engine/engine.php';
		$engine = new JSFunc();
		$_GET['p']=$_POST['short_name'];
		$page = array('short_name'=>$_POST['short_name'],'name'=>$_POST['name'],'value'=>$_POST['html'],'id'=>$_POST['id']);
		$page['id'] = $engine->settingsSet($page, 'pages');
	}
	else
	{
		if (!isset($_GET['p']) || $_GET['p'] === 'add')
			$page = array('id'=>'add','short_name'=>'new', 'name'=>'Новая страница', 'value'=>'');
		else
			$page = $engine->settingsGet(array('id','short_name','name','value'), 'pages', array('short_name'=>$_GET['p']))[0];
	}
?>
<form method='POST' action="/?b=pages<?=isset($_GET['p']) ? '&p='.$_GET['p'] : ''?>">
	<input type='hidden' name='id' value='<?=$page['id']?>'>
	<div>Краткое имя:</div>
	<div><input type='text' name='short_name' value='<?=$page['short_name']?>'></div>
	<div>Полное имя:</div>
	<div><input type='text' name='name' value='<?=$page['name']?>'></div>
	<div>Текст страницы:</div>
	<div>
		<textarea name='html'>
			<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$page['value'])?>
		</textarea>
	</div>
</form>