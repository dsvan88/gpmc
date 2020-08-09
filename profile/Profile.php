<? if (isset($_SESSION['id']) && $_SESSION['id'] > 0) :
	$_GET['profile'] = (int) $_GET['profile'];
	$a_genders = array('<i>Инкогнито</i>', 'Господин', 'Госпожа', 'Некто');
	$a_statuses = array('Гость клуба', 'Резидент клуба', 'Основатель клуба');
	$a_cats = array('C', 'B', 'A');
	$my = $_GET['profile'] == $_SESSION['id'] ? true : false;
	$user = $engine->GetPlayerData(array('name','rank','status','fio','birthday','gender','email','game_credo','live_credo','avatar'),array('id'=>$_GET['profile']));
?>
	<script type='text/javascript'>
	var uID = <?=$_GET['profile']?>;
	</script>
	<div id = 'Profile_MainDiv'>
		<div id ='Profile_CaptionDiv'>Профиль игрока <b><?=$a_genders[$user['gender']],' ',$user['name']?></b></div>
		<div id='Profile_PlayerData'>
			<div id='Profile_PhotoDiv'>
				<div id="Profile_PhotoPlace">
				<? if ($my) : ?>
					<img class='my_avatar' src ='<?=$user['avatar'] === '' ? $settings['img']['empty_avatar']['value'] : '/gallery/users/'.$_GET['profile'].'/'.$user['avatar']?>'/>
				<?else:?>
					<img class='user_avatar' src ='<?=$user['avatar'] === '' ? $img_genders[$user['gender']] : '/gallery/users/'.$_GET['profile'].'/'.$user['avatar']?>'/>
				<?endif;?>
				</div>
				<form enctype="multipart/form-data" action="php_scripts/upload_file.php" method="POST" style='display:none'>
					<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
					Добавить фото: <input name="userfile" type="file" accept="image/jpeg,image/png"/>
					<input type="submit" value="Отправить файл" />
				</form>					
			</div>
			<div id='Profile_InfoDiv'>
				<div class="Profile_tabs active" id="Profile_PlayerInfo">Личные данные</div>
				<div class="Profile_tabs" id="Profile_PlayerScore">Рейтинг общий</div>
				<div class="Profile_tabs" id="Profile_PlayerRedScore">Рейтинг мирным</div>
				<div class="Profile_tabs" id="Profile_PlayerBlackScore">Рейтинг мафией</div>
				<div id="Profile_ScoreTextPlace">
					<? include dirname(__FILE__).'/PlayerInfo.php' ?>
				</div>
			</div>
		</div>
		<div id='Profile_PlayerPhrases'>
			Немного о себе:
			<div class='InfoRow'><span class='InfoCaption'>Игровое кредо:</span><?if ($my) :?><a class='EditPencilTA' id='game_credo'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><a class='ApplyTA' id='game_credo' title='Принять' alt='Принять'><img src = '<?=$settings['img']['apply']['value']?>'/></a><?endif?></div>
			<div class='InfoRow'><span id="game_credo"><?=$user['game_credo'] === '' ? $def : str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$user['game_credo'])?></span></div>
			<div class='InfoRow'><span class='InfoCaption'>Жизненная позиция:</span><?if ($my) :?><a class='EditPencilTA' id='live_credo'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><a class='ApplyTA' id='live_credo' title='Принять' alt='Принять'><img src = '<?=$settings['img']['apply']['value']?>'/></a><?endif?></div>
			<div class='InfoRow'><span id="live_credo"><?=$user['live_credo'] === '' ? $def : str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$user['live_credo'])?></span></div>
		</div>;
		<div id='Profile_OthersPhrases'>
		<?if ($my):?>
			<div>Немного про Вас:</div>
		<?else:?>
			<div>Пара слов об игроке:</div>
			<div class='span_buttons_place'>
				<span class='span_button' id='AddComment'><img src='<?=$settings['img']['plus']['value']?>'/>Добавить коментарий<img src='<?=$settings['img']['plus']['value']?>'/></span>
			</div>
			<form id='AddComment' style='display:none'>
				<textarea name='comment'></textarea><br>
				<span class='span_button' id='SaveComment'><img src='<?=$settings['img']['apply']['value']?>'/>Сохранить<img src='<?=$settings['img']['apply']['value']?>'/></span>
			</form>
			<br>
		<?endif?>
			<div id ='Profile_Comments'>
				<? $comments = $engine->GetComments('user',$_GET['profile']);
				if ($comments !== false)
					for ($x=0;$x<count($comments);$x++):?>
						<div class='CommentsUser'><a href='/?profile=<?=$comments[$x]['author']?>'><b><?=$engine->GetPlayerName($comments[$x]['author'])?></b></a>:</div>
						<div class='CommentsText'><?=str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$comments[$x]['txt'])?></div>
					<?endfor;
				else echo $def;?>
			</div>
		</div>
	</div>
<?else:?>
Не <a id="Welcome">авторизованные</a> гости сайта - не могут просматривать личные профили гостей и резидентов клуба. Приносим свои извинения!
<?endif?>