<header>
	<div class="header">
		<div class="header__main-logo">
			<a href="http://<?=$_SERVER['SERVER_NAME']?>/" >
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
					<a class="login">Войдите</a>
					<br>или<br>
					<a class="user-register">Зарегистрируйтесь</a>
				</div>
			<?endif?>
		</div>
	</div>
	<menu>
		<li><a href='/?trg=news'><span>Новости</span></a></li>
		<li><a href='/?trg=booking'><span>Запись на игру</span></a></li>
		<li><a href='/?trg=rules'><span>Правила</span></a></li>
		<li><a href='/?trg=about'><span>О нас</span></a></li>
	</menu>
</header>