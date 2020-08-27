<? $i = isset($_POST['i']) ? $_POST['i'] : $i; ?>
<div class="gamer">
	<span class="num"><?=($i+1)?>.</span>
	<div class="gamer__info">
		<input name="gamer[<?=$i?>]" type="text" class="input_name" value ="<?=isset($EveningData['gamers'][$i]) ? $EveningData['gamers'][$i]['name'] : ''?>"/>
		<input name="g_time[<?=$i?>]" type="text" class="input_time timepicker" value ="<?=isset($EveningData['gamers_info'][$i]['arrive']) ? $EveningData['gamers_info'][$i]['arrive'] : '17:00'?>"/>
		<select name="duration[<?=$i?>]" class="input_tobe">
			<option value='0'<?=$EveningData['gamers_info'][$i]['duration'] == '0' ? ' selected ' : ''?>></option>
			<option value='1'<?=$EveningData['gamers_info'][$i]['duration'] == '1' ? ' selected ' : ''?>>1-2 игры</option>
			<option value='2'<?=$EveningData['gamers_info'][$i]['duration'] == '2' ? ' selected ' : ''?>>2-3 игры</option>
			<option value='3'<?=$EveningData['gamers_info'][$i]['duration'] == '3' ? ' selected ' : ''?>>3-4 игры</option>
		</select>
		<? if (!isset($_POST['i'])):?>
			<span class='img-button img-delete' id='<?=$i.'_'.$EveningData['gamers'][$i]['id']?>'>
			<?=$engine->checkAndPutImage('/css/images/minus.png','Отписать')?>
			</span>
		<?else:?>
			<span class='img-button img-non-delete'>
			<?=$engine->checkAndPutImage('/css/images/minus.png','Отписать')?>
			</span>
		<?endif?>
	</div>
</div>
