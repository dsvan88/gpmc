<? $user_data = $engine->GetPlayerData(array($_POST['c']),array('id'=>$_SESSION['id'])); ?>
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