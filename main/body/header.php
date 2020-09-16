<header>
	<div class="header">
		<div class="header__main-logo">
			<a href="http://<?=$_SERVER['SERVER_NAME']?>/" >
				<?=$engine->checkAndPutImage($settings['img']['MainLogo']['value'],['title'=>$settings['img']['MainLogo']['name']])?>
			</a>
		</div>
		<div class="header__title"><?=$settings['txt']['header']['value']?></div>
		<div class="header__login-place">
			<h4>Добро пожаловать!</h4>
			<?	if (isset($_SESSION['id'])): 
				$userData = $engine->GetGamerData(array('name','fio','rank'), array('id'=>$_SESSION['id']));
				$statuses = array('Гость', 'Резидент', 'Основатель');
				$ranks = array('C', 'B', 'A');
				?>
				<div>
					<div class="info-row">
						<label>Обращение:</label>
						<span><?=$genders[$_SESSION['gender']]?></span>
					</div>
					<div class="info-row">
						<label>Псведоним:</label>
						<a href='<?='/?profile=',$_SESSION['id']?>'>
							<?=$userData['name']?>
						</a>
					</div>
					<div class="info-row">
						<label>Имя:</label>
						<span><?=$userData['fio']?></span>
					</div>
					<div class="info-row">
						<label>Статус:</label>
						<span><?=$statuses[$_SESSION['status']]?></span>
					</div>
					<div class="info-row">
						<label>Ранг:</label>
						<span><?=$ranks[$userData['rank']]?></span>
					</div>
					<div class="info-row">
						<a href='<?='/?profile=',$_SESSION['id']?>'>Профиль</a>
						<a href='#' data-action="logout">Выйти</a>
					</div>
				</div>
			<? else: ?>
				<form>
					<div class="header__login-place__inputs">
						<input type="text" name="login" value="" placeholder="Логин/Псевдоним/Почта">
						<input type="password" name="pass" placeholder="Пароль">
					</div>
					<div class="header__login-place__links">
					<a data-action-type="header-login">Войдите</a>
					<br>или<br>
					<a data-form-type="user-register">Зарегистрируйтесь</a>
					</div>
					<!-- <a data-form-type="login">Войдите</a>
					<br>или<br>
					<a data-form-type="user-register">Зарегистрируйтесь</a> -->
				</form>
			<?endif?>
		</div>
	</div>
	<menu>
		<li><a href='/?trg=news'><span>Новости</span></a></li>
		<li><a href='/?trg=booking'><span>Запись на игру</span></a></li>
		<? if ($EveningData['start']) :?>
			<li><a href='/?trg=evening'><span>Вечер игры!</span></a></li>
		<?endif?>
		<li><a href='/?trg=rules'><span>Правила</span></a></li>
		<li><a href='/?trg=about'><span>О нас</span></a></li>
	</menu>
</header>