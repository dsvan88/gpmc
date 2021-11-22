<? $i = isset($_POST['id']) ? $_POST['id'] : $i; ?>
<div class="gamer">
	<span class="num"><?=($i+1)?>.</span>
	<div class="gamer__info">
		<input name="gamer" type="text" class="input_name" value ="<?=isset($EveningData['gamers'][$i]) ? $EveningData['gamers'][$i]['name'] : ''?>"/>
		<input name="arrive" type="text" class="timepicker" value ="<?=isset($EveningData['gamers_info'][$i]['arrive']) ? $EveningData['gamers_info'][$i]['arrive'] : '17:00'?>"/>
		<select name="duration">
			<option value='0'<?=$EveningData['gamers_info'][$i]['duration'] == '0' ? ' selected ' : ''?>></option>
			<option value='1'<?=$EveningData['gamers_info'][$i]['duration'] == '1' ? ' selected ' : ''?>>1-2 игры</option>
			<option value='2'<?=$EveningData['gamers_info'][$i]['duration'] == '2' ? ' selected ' : ''?>>2-3 игры</option>
			<option value='3'<?=$EveningData['gamers_info'][$i]['duration'] == '3' ? ' selected ' : ''?>>3-4 игры</option>
		</select>
		<? if (!isset($_POST['id'])):?>
			<div class='img-delete' id='<?=$i.'_'.$EveningData['gamers'][$i]['id']?>' data-action-type="discharge-gamer">
			<?=$engine->inputImage('/css/images/minus.png',['title'=>'Отписать'])?>
			</div>
		<?else:?>
			<div class='img-non-delete'>
			<?=$engine->inputImage('/css/images/minus.png',['title'=>'Отписать'])?>
			</div>
		<?endif?>
	</div>
</div>
