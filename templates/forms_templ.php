<?php
$check = array_search($_POST['t'], array('login','add_new','reg_me','edit_my','rec_me','admin_login'), true);
if ($check === false)
$engine_set = 'GETS';
include $root_path.'/engine/engine.php';
include $root_path.'/engine/check_admin.php';
$engine = new GetDatas();
$captions=array('Добро пожаловать в <br>'.MAFCLUB_NAME.'!','Добавление игрока','Регистрация нового игрока','Изменение личных данных','Регистрация нового участника');

if ($check < 5) :?>
<img class = 'left' src='../css/images/gmpc_emblem1.png' alt='emblem' title='<?=MAFCLUB_SNAME?>'/>
<img class = 'right' src='../css/images/gmpc_emblem.png' alt='emblem' title='<?=MAFCLUB_SNAME?>'/>
<?endif?>

<div class='FormMaket'>
	<span class='caption'><?=$captions[$check]?></span>
	<? if ($_POST['t'] === 'login') :?>
	<form id='LoginForm'>
		<div class ='input_row'><span><b>Логин/Ник/Почта</b></span><input type='text' name='login' class='input_name'/></div>
		<div class ='input_row'><span><b>Пароль</b></span><input type= 'password' name='pass'/></div>
		<div class ='input_row'><button id ='LogInButton'>Войти</button></div>
		<a>Забыли пароль?</a><a class='right' id='RegisterNewUser'>Регистрация</a>
	</form>
	<?elseif ($_POST['t'] === 'add_new') :?>
	<form id='AddEveningGamer'>
		<div class ='input_row'><input name="new_gamer" type="text" class="input_gamer" value ="<?=$players[$i]?>"/></div>
		<div class ='input_row'><button id='AddNewGamer'>Добавить</button></div>
		<span>* Введите игровой ник игрока.</span>
	</form>
	<?elseif ($_POST['t'] === 'reg_me') : ?>
	<form id='RegisterForm'>
		<div class ='input_row'><span><b>Логин</b></span><input required name="username" type="text" class="input_name" value =""/></div>
		<div class ='input_row'><span><b>Ник в игре</b></span><input required name="player_name" type="text" class="input_name" value =""/></div>
		<div class ='input_row'><span><b>Электронная почта</b></span><input name="email" type="text" class="input_name" value =""/></div>
		<div class ='input_row'><span><b>Пароль</b></span><input required name="pass" type="password" class="input_name" value =""/></div>
		<div class ='input_row'><span><b>Повторите пароль</b></span><input required name="chk_pass" type="password" class="input_name" value =""/></div>
		<div class ='input_row'><button id='CheckAndReg'>Зарегистрировать</button></div>
		<a id='Welcome'>Назад</a>
	</form>
	<?elseif ($_POST['t'] === 'edit_my') :
		$user_data = $engine->GetGamerData(array($_POST['c']),array('id'=>$_SESSION['id']));
	?>
	<form id='Form_EditUserInfoRow'>
		<div class ='input_row'>
			<? if ($_POST['c'] !== 'gender'):?>
				<input name="<?=$_POST['c']?>" type="text" class="input_gamer<?=($_POST['c'] === 'birthday' ? ' datepick' :'')?>" value ="<?=($_POST['c'] === 'birthday' ? date('d.m.Y',$user_data[$_POST['c']]) : $user_data[$_POST['c']])?>"/>
			<? else: 
				$genders = array('Инкогнито','Господин','Госпожа','Некто'); ?>
				<select name="gender" class="select_gamer">
					<?for ($x=0;$x<count($genders);$x++):?>
						<option value='<?=$x?>'<?=$user_data['gender'] == $x ? ' selected' : ''?>><?=$genders[$x]?></option>
					<?endfor?>
				</select>
			<?endif?>
		</div>
		<div class ='input_row'><button id='EditUserInfoRow'>Изменить</button></div>
		<span>* Введите новые данные.</span>
	</form>
	<?elseif ($_POST['t'] === 'rec_me') :	?>
	<form id='Form_MyConfirm'>
		<?$t = date('H:i',$engine->GetNearEveningData('date'))?>
		<div class ='input_row'>Начало игр, запланировано на <b><?=$t?></b>!</div>
		<div class ='input_row'><span class='input_half_row'><b>Я буду к</b>:</span>
			<input name='plan_arrive' type='text' class='input_half_row timepicker' value='<?=$t?>'>
		</div>
		<div class ='input_row'><span class='input_half_row'><b>План</b> (1 игра ~ час):</span>
			<select name="plan_tobe" class="input_half_row">
				<option value='0'>До конца!</option>
				<option value='1'>На 1-2 игры</option>
				<option value='2'>На 2-3 игры</option>
				<option value='3'>На 3-4 игры</option>
			</select>
		</div>
		<div class ='input_row'><button id='AddMe'>Записаться!</button></div>
		<span>* Уточните Ваши планы.</span>
	</form>
	<?elseif ($_POST['t'] === 'admin_login') :?>
	<form id='Form_AdminLogin'>
		<div class ='input_row'><span><b>Логин</b></span><input type='text' name='login'/></div>
		<div class ='input_row'><span><b>Пароль</b></span><input type='password' name='pass'/></div>
		<input type='hidden' name='ap' value='<?=rand(1,1000)?>'/>
		<div class ='input_row'><button id ='LogInButton'>Войти</button></div>
	</form>
	<?endif?>
</div>