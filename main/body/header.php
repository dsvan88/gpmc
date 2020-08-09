<header>
	<div class="header">
		<div class="header__main-logo">
			<a href="http://<?=$_SERVER['SERVER_NAME']?>/">
				<?=$engine->checkAndPutImage($settings['img']['MainLogo']['value'],$settings['img']['MainLogo']['name'])?>
			</a>
		</div>
		<div class="header__title"><?=$settings['txt']['header']['value']?></div>
		<div class="header__login-place">
			<h4>Добро пожаловать!</h4>
			<?	if (isset($_SESSION['id'])): ?>
				<div>
					<?=$genders[$_SESSION['gender']]?>
					<a href='<?='/?profile=',$_SESSION['id']?>' id="aProfile">
						<?=$engine->GetPlayerData(array('name'), array('id'=>$_SESSION['id']))['name']?>
					</a>
					<br>
					<a href='#' id='LogOut'>Выйти</a>
				</div>
			<? else: ?>
				<div>
					<a id="Welcome">Войдите</a> или <a id="RegisterNewUser">Зарегистрируйтесь</a>
				</div>
			<?endif?>
		</div>
	</div>
	<menu>
		<span>Новости</span>
		<span>Записаться на игру</span>
		<span>Правила</span>
		<span>О нас</span>
	</menu>
</header>