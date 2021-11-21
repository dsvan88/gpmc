<? if (isset($_SESSION['id']) && $_SESSION['id'] > 0) :
	$_GET['profile'] = (int) $_GET['profile'];
	$a_genders = array('<i>Инкогнито</i>', 'Господин', 'Госпожа', 'Некто');
	$a_genders_colors = ['#8ee6d826','#8ea1e626','#e68e8e26','#a18ee626;'];
	$a_statuses = array('Гость клуба', 'Резидент клуба', 'Основатель клуба');
	$a_cats = array('C', 'B', 'A');
	$my = $_GET['profile'] == $_SESSION['id'] ? true : false;
	$user = $engine->getGamerData(array('name','rank','status','fio','birthday','gender','email','game_credo','live_credo','avatar'),array('id'=>$_GET['profile']));
?>
	<div class="profile" style="background-color:<?=$a_genders_colors[$user['gender']]?>" data-user-id="<?=$_GET['profile']?>">
		<h3 class="profile__title">
			Профиль игрока 
			<b>
			<?=$a_genders[$user['gender']],' ',$user['name']?>
			</b>
		</h3>
		<div class='profile__upper-block'>
			<div class='profile__upper-block__photo-block'>
			<? if ($my) : 
				if ($user['avatar'] === ''):?>
					<div class="profile__upper-block__photo-block__photo-place" data-action-type="crop-new-avatar">
						<?=$engine->checkAndPutImage($settings['img']['empty_avatar']['value'],['title'=>$settings['img']['empty_avatar']['name']])?>
					</div>
				<?else:?>
					<div class="profile__upper-block__photo-block__photo-place" data-form-type="user-avatar" data-user-id="<?=$_GET['profile']?>">
						<?=$engine->checkAndPutImage('/gallery/users/'.$_GET['profile'].'/'.$user['avatar'],['title'=>'Аватар користувача'])?>
					</div>
				<?endif?>
			<?else:?>
					<div class="profile__upper-block__photo-block__photo-place" data-form-type="user-avatar" data-user-id="<?=$_GET['profile']?>">
						<?=$engine->checkAndPutImage($user['avatar'] === '' ? $img_genders[$user['gender']] : '/gallery/users/'.$_GET['profile'].'/'.$user['avatar'],['title'=>$settings['img']['empty_avatar']['name']])?>
					</div>
			<?endif?>
				
				<!-- <form enctype="multipart/form-data" action="php_scripts/upload_file.php" method="POST" style='display:none'>
					<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
					<input name="userfile" type="file" accept="image/jpeg,image/png"/>
					<input type="submit" value="Отправить файл" />
				</form>					 -->
			</div>
			<div class='profile__upper-block__info-block'>
				<menu class="profile__upper-block__info-block__menu">
					<li class="active" data-profile-info="gamer-info">Личные данные</li>
					<li data-profile-info="gamer-score">Рейтинг общий</li>
					<li data-profile-info="gamer-red-score">Рейтинг мирным</li>
					<li data-profile-info="gamer-black-score">Рейтинг мафией</li>
				</menu>
				<div class="profile__upper-block__info-block__content">
					<? include dirname(__FILE__).'/gamer-info.php' ?>
				</div>
			</div>
		</div>
		<div class='profile__middle-block'>
			<h4>Немного о себе:</h4>
			<div class='info-row'>
				<span class='info-row__title'>
					Игровое кредо:
				</span>
				<?if ($my) :?>
					<a class='info-row__edit' data-action-type="edit-row" data-edit-row='game_credo'>
						<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
					</a>
					<a class='info-row__apply' data-action-type="save-row" data-save-row='game_credo' title='Принять' alt='Принять'>
						<?=$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']])?>
					</a>
				<?endif?>
				<p id="game_credo">
					<?=$user['game_credo'] === '' ? $def : str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$user['game_credo'])?>
				</p>
			</div>
			<div class='info-row'>
				<span class='info-row__title'>
					Жизненная позиция:
				</span>
				<?if ($my) :?>
					<a class='info-row__edit' data-action-type="edit-row" data-edit-row='live_credo'>
						<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
					</a>
					<a class='info-row__apply' data-action-type="save-row" data-save-row='live_credo' title='Принять' alt='Принять'>
						<?=$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']])?>
					</a>
				<?endif?>
				<p id="live_credo">
					<?=$user['live_credo'] === '' ? $def : str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$user['live_credo'])?>
				</p>
			</div>
		</div>
		<div class='profile__lower-block'>
			<?if ($my):?>
			<h4>Немного про Вас:</h4>
			<?else:?>
			<h4>Пара слов об игроке:</h4>
			<div class='profile__lower-block__buttons'>
				<span class='span_button' data-action-type='show-comment-form'>
					<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
					Добавить коментарий
					<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
				</span>
			</div>
			<form id='addComment' style='display:none'>
				<textarea name='comment'></textarea>
				<span class='span_button' data-action-type='save-comment'>
					<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
						Сохранить
					<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
				</span>
			</form>
			<?endif?>
			<div id ='Profile_Comments'>
				<? $comments = $engine->GetComments('user',$_GET['profile']);
				if ($comments !== false)
					for ($x=0;$x<count($comments);$x++):?>
						<h4><a href='/?profile=<?=$comments[$x]['author']?>'><b><?=$engine->getGamerName($comments[$x]['author'])?></b></a>:</h4>
						<p><?=str_replace(array('!BR!','«', '»'),array('<br>','"','"'),$comments[$x]['txt'])?></p>
					<?endfor;
				else echo $def;?>
			</div>
		</div>
	</div>
<?else:?>
Не&nbsp;<a data-form-type="login">авторизованные</a>&nbsp;гости сайта - не могут просматривать личные профили гостей и резидентов клуба. Приносим свои извинения!
<?endif?>