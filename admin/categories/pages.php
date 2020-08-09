<?
	if (isset($_POST['shname']))
	{
		$engine_set = 'JSFUNC';
		require $root_path.'/engine/engine.php';
		$engine = new JSFunc();
		$_GET['p']=$_POST['shname'];
		$page = array('shname'=>$_POST['shname'],'name'=>$_POST['name'],'value'=>$_POST['html'],'id'=>$_POST['id']);
		$page['id'] = $engine->SetSettings($page, 'pages');
	}
	else
	{
		if (!isset($_GET['p']) || $_GET['p'] === 'add')
			$page = array('id'=>'add','shname'=>'new', 'name'=>'Новая страница', 'value'=>'');
		else
			$page = $engine->GetSettings(array('id','shname','name','value'), 'pages', array('shname'=>$_GET['p']))[0];
	}
?>
<form method='POST' action="/?b=pages<?=$_GET['p'] ? '' : '&p='.$_GET['p']?>">
	<input type='hidden' name='id' value='<?=$page['id']?>'>
	<div>Краткое имя:</div>
	<div><input type='text' name='shname' value='<?=$page['shname']?>'></div>
	<div>Полное имя:</div>
	<div><input type='text' name='name' value='<?=$page['name']?>'></div>
	<div>Текст страницы:</div>
	<div>
		<textarea name='html' id='editor'>
			<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$page['value'])?>
		</textarea>
	</div>
</form>