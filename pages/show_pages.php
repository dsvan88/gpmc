<div class="content__page">
<?
$defaults = array('history','voting','rating','news','booking', 'evening','gamers');
if (in_array($_GET['trg'],$defaults,true) ): //&& (isset($_SESSION['status']) && $_SESSION['status'] > 0)
	require 'show_'.$_GET['trg'].'.php';
else:
	$page = $engine->settingsGet(array('value'),'pages',array('shname'=>$_GET['trg']))[0];?>
	<div class="content__page__text">
		<?  if ($page !== false): ?>
			<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$page['value'])?>
		<?else:?>
			<span>Соответствующей страницы не найдено!</span>
		<?endif?>
	</div>
<?endif?>
</div>