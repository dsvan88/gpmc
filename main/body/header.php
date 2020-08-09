<div id='MainHeader'>
	<a id='MainLogo' href="http://<?=$_SERVER['SERVER_NAME']?>/"><img src='<?=$settings['img']['MainLogo']['value']?>' alt="<?=$settings['img']['MainLogo']['name']?>" title="<?=$settings['img']['MainLogo']['name']?>"/></a>
	<div id='MainHeaderText'><?=$settings['txt']['header']['value']?></div>
		<div id='LogInDiv'>
		<?	if (isset($_SESSION['id'])): ?>
			<div>Добро пожаловать!</div>
			<div><?=$genders[$_SESSION['gender']]?> <a href='<?='/?profile=',$_SESSION['id']?>' id="aProfile"><?=$engine->GetPlayerData(array('name'), array('id'=>$_SESSION['id']))['name']?></a><br><a href='#' id='LogOut'>Выйти</a></div>
		<? else: ?>
			<div>Добро пожаловать!</div>
			<div><a id="Welcome">Войдите</a> или <a id="RegisterNewUser">Зарегистрируйтесь</a></div>
		<?endif?>
	</div>
</div>