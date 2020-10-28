<div class='admin-panel'>
<?	
	$blocks = array('settings'=>'Настройки сайта', 'pages'=>'Страницы сайта', 'another'=>'Что-то ещё, что ещё не придумал');
	if (!isset($_GET['b']))
	{
		$_GET['b'] = 'settings';
		$_GET['p'] = 'texts';
	}
?>
	
	<div class="admin-panel__header">
			<nav class="admin-panel__header__navigation">
				<a href='/'>Админ панель</a>
				-> <a href='/?b=<?=$_GET['b']?>'><?=$blocks[$_GET['b']]?></a>
					-> <a href='/?b=<?=$_GET['b']?>&p=<?=$_GET['p']?>'><?=$_GET['p']?></a>
			</nav>
			<h2><?=$blocks[$_GET['b']]?></h2>
		</div>
		<div class="admin-panel__body">
			<div class='admin-panel__body__menu'>
				<ul id='settings'>
					<h4 data-action-type="toggle-list-items">Настройки сайта</h4>
					<li id='texts'><a href='/?b=settings&p=texts'>Тексты сайта</a></li>
					<li id='images'><a href='/?b=settings&p=images'>Изображения</a></li>
					<li id='users'><a href='/?b=settings&p=users'>Пользователи</a></li>
					<li id='points'><a href='/?b=settings&p=points'>Рейтинговые баллы</a></li>
					<li id='news'><a href='/?b=settings&p=news'>Новости</a></li>
				</ul>
				<ul id='pages'>
					<h4 data-action-type="toggle-list-items">Страницы сайта</h4>
					<?$pages = $engine->GetSettings(array('shname','name'), 'pages');
					for($x=0;$x<count($pages);$x++):?>
						<li id='<?=$pages[$x]['shname']?>'><a href='/?b=pages&p=<?=$pages[$x]['shname']?>'><?=$pages[$x]['name']?></a></li>
					<?endfor?>
					<li id='add'>
						<a href='/?b=pages&p=add'>Добавить</a>
					</li>
				</ul>
				<ul id='another'>
					<h4 data-action-type="toggle-list-items">Что-то ещё, что ещё не придумал</h4>
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
			<div class='admin-panel__body__content'>
				<?if ($_GET['b'] === 'pages'):
					require 'categories/pages.php';
				elseif ($_GET['b'] === 'settings'):
					require 'categories/settings.php';
				else:?>
				Тело блока
				<?endif?>
			</div>
		</div>
		<div class=admin-panel__footer'>Админ панель сайта <?=MAFCLUB_SNAME?></div>
</div>