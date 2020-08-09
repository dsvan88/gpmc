<div id="Pages_Body">
<?
$defaults = array('history','voting','rating');
if (in_array($_GET['trg'],$defaults,true) && (isset($_SESSION['status']) && $_SESSION['status'] > 0)):
	require 'show_'.$_GET['trg'].'.php';
else:
	$page = $engine->GetSettings(array('value'),'pages',array('shname'=>$_GET['trg']))[0];
	if ($page !== false): ?>
		<div id="Pages_Body_Text">
			<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$page['value'])?>
		</div>
	<?else:?>
		<div id="Pages_Body_Text">
		Соответствующей страницы не найдено!
		</div>
	<?endif;
endif;?>
</div>