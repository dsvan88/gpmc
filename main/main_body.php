<header class="header">
	<div class="header__upper">
		<div class="logo">
			<a href="http://<?=$_SERVER['SERVER_NAME']?>/">
				<?=$engine->checkAndPutImage($settings['img']['MainLogo']['value'],['title'=>$settings['img']['MainLogo']['name']])?>
			</a>
		</div>
		<div class="title"><?=$settings['txt']['header']['value']?></div>
		<form class="login-place">
			<h4 class="login-place__title">Добро пожаловать!</h4>
			<?	if (isset($_SESSION['id'])): 
				$ranks = array('C', 'B', 'A');
				?>
				<div>
					<div class="info-row">
						<label>Обращение:</label>
						<span><?=$genders[$_SESSION['gender']]?></span>
					</div>
					<div class="info-row">
						<label>Псведоним:</label>
						<a href='<?='/?profile=',$_SESSION['id']?>'
						<?=($userData['ar'] > 0 ? 'data-action-type="admin-panel"': '')?>>
							<?=$userData['name']?>
						</a>
					</div>
					<div class="info-row">
						<label>Имя:</label>
						<span><?=$userData['fio']?></span>
					</div>
					<div class="info-row">
						<label>Статус:</label>
						<span>
						<? if ($userData['ar'] > 0):?>
							<a data-action-type="admin-panel" data-action-mode="admin">
								<?=$statuses[$_SESSION['status']]?>
							</a>
						<?else:?>
							<?=$statuses[$_SESSION['status']]?>
						<?endif;?>
						</span>
					</div>
					<div class="info-row">
						<label>Ранг:</label>
						<span><?=$ranks[$userData['rank']]?></span>
					</div>
					<div class="info-row">
						<a href='<?='/?profile=',$_SESSION['id']?>'
						<?=($userData['ar'] > 0 ? 'data-action-type="admin-panel"': '')?>>
						Профиль
						</a>
						<a href='#' data-action="logout">выйти</a>
					</div>
				</div>
			<? else: ?>
				<div class="login-place__input-wrapper">
					<input type="text" name="login" value="" placeholder="Логин/Псевдоним/Почта">
				</div>
				<div class="login-place__input-wrapper">
					<input type="password" name="pass" placeholder="Пароль">
				</div>
				<div class="login-place__input-wrapper">
					<button data-action-type="header-login" href="#">Войти</button>
				</div>
				<div class="login-place__input-wrapper">
					<span>или</span>
				</div>
				<div class="login-place__input-wrapper">
					<a data-form-type="user-register" href="#">Зарегистрироваться</a>
				</div>
			<?endif?>
		</form>
	</div>
	<?if (!isset($_SESSION['ba']) || $_SESSION['ba'] < 1):?>
	<div class="header__lower">
		<nav class="menu">
			<li><a href='/?trg=news'><span>Новости</span></a></li>
			<li><a href='/?trg=booking'><span>Запись на игру</span></a></li>
			<? if ($EveningData['start']) :?>
				<li><a href='/?trg=evening'><span>Вечер игры!</span></a></li>
			<?endif?>
			<li><a href='/?trg=gamers'><span>Игроки</span></a></li>
			<li><a href='/?trg=rules'><span>Правила</span></a></li>
			<li><a href='/?trg=about'><span>О нас</span></a></li>
		</nav>
	</div>
	<?endif;?>
</header>
<main class='content'>
<?
	if (isset($_GET['profile']))
		include $root_path.'/profile/profile.php';
	elseif(isset($_GET['trg']))
		include $root_path.'/pages/show_pages.php';
	elseif(isset($_GET['g_id']))
		include $root_path.'/game/game.php';
?>
</main>
<footer><?=$settings['txt']['footer']['value']?></footer>