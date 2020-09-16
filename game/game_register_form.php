<form id="beReadyForGame" method="POST">
<div class="evening-active__manager">
	<label for="manager" >Ведущий: </label>
	<input id="manager" name="manager" type="text" class="input_name" value =""/>
</div>
<ol class="evening-active__player-list">
<?
$i=-1;
$tmp = [];
for ($x=0;$x<count($players);$x++)
	if (strpos($players[$x],'tmp_user') === false)
		$tmp[++$i] = $players[$x];

$i=-1;
while(++$i<=9):
?>
	
		<li class="player" >
			<input id="name_<?=$i?>" name="player" type="text" class="input_name" value ="<?=isset($tmp[$i]) ? $tmp[$i] : ''?>"/>
			<select name="role" class="select_role">
				<option value='0'> </option>
				<option value='1'>Мафия</option>
				<option value='2'>Дон мафии</option>
				<option value='4'>Шериф</option>
			</select>
		</li>
	
<?endwhile;?>
</ol>
	<div class='evening-active__buttons'>
		<span class='span_button' data-action-type='start-game'>
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
			Начать
			<?=$engine->checkAndPutImage($settings['img']['apply']['value'])?>
		</span>
	</div>
	<!-- <div class='SepDiv'><button id="StartGame" class="menu_button">Начать</button></div> -->
</form>