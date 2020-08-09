<div id='APMainBody'>
<?	
	$blocks = array('settings'=>'Настройки сайта', 'pages'=>'Страницы сайта', 'another'=>'Что-то ещё, что ещё не придумал');
	if (!isset($_GET['b']))
	{
		$_GET['b'] = 'settings';
		$_GET['p'] = 'texts';
	}
?>
	<nav><a href='/'>Админ панель</a>
		-> <a href='/?b=<?=$_GET['b']?>'><?=$blocks[$_GET['b']]?></a>
			-> <a href='/?b=<?=$_GET['b']?>&p=<?=$_GET['p']?>'><?=$_GET['p']?></a>
	</nav>
	<div id='AP_Caption'><?=$blocks[$_GET['b']]?></div>
	<div id='AP_Menu'>
		<ul id='settings'> Настройки сайта
			<li id='texts'><a href='/?b=settings&p=texts'>Тексты сайта</a></li>
			<li id='images'><a href='/?b=settings&p=images'>Изображения</a></li>
			<li id='users'><a href='/?b=settings&p=users'>Пользователи</a></li>
			<li id='points'><a href='/?b=settings&p=points'>Рейтинговые баллы</a></li>
		</ul>
		<ul id='pages'> Страницы сайта
			<?$pages = $engine->GetSettings(array('shname','name'), 'pages');
			for($x=0;$x<count($pages);$x++):?>
				<li id='<?=$pages[$x]['shname']?>'><a href='/?b=pages&p=<?=$pages[$x]['shname']?>'><?=$pages[$x]['name']?></a></li>
					<?endfor?>
			<li id='add'><a href='/?b=pages&p=add'>Добавить</a></li>
		</ul>
		<ul id='another'> Что-то ещё, что ещё не придумал
			<li id='p1'><a href='/?b=another&p=p1'>1 пункт меню</a></li>
			<li id='p2'><a href='/?b=another&p=p2'>2 пункт меню</a></li>
			<li id='p3'><a href='/?b=another&p=p3'>3 пункт меню</a></li>
		</ul>
		<script type='text/javascript'>
			$(function(){
				$('ul#<?=isset($_GET['b']) ? $_GET['b'] : 'settings'?>').addClass('active');
				$('ul.active li#<?=isset($_GET['p']) ? $_GET['p'] : 'texts'?>').addClass('active');
			});
		</script>
	</div>
	<div id='AP_Body'>
		<?if ($_GET['b'] === 'pages'):
			require 'categories/pages.php';
		elseif ($_GET['b'] === 'settings'):
			require 'categories/settings.php';
		else:?>
		Тело блока
		<?endif?>
	</div>
	<div id='AP_Footer'>Админ панель сайта <?=MAFCLUB_SNAME?></div>
</div>