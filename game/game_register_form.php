<form id="tempForm" method="POST">
<div class="pl"><span class="span_manager">Ведущий: </span><input name="manager" type="text" class="input_name" value =""/></div>
<?
$i=-1;
$tmp = [];
for ($x=0;$x<count($players);$x++)
	if (strpos($players[$x],'tmp_user') === false)
		$tmp[++$i] = $players[$x];
$i=-1;
while(++$i<=9):
?>
	<div class="pl">
		<span class="num"><?=($i+1)?></span><div class="players">
			<input id="name_<?=$i?>" name="player[<?=$i?>]" type="text" class="input_name" value ="<?=isset($tmp[$i]) ? $tmp[$i] : ''?>"/>
			<select name="role[<?=$i?>]" class="select_role">
				<option value='0'> </option>
				<option value='1'>Мафия</option>
				<option value='2'>Дон мафии</option>
				<option value='4'>Шериф</option>
			</select>
		</div>
	</div>
<?endwhile;?>
	<div class='SepDiv'><button id="StartGame" class="menu_button">Начать</button></div>
</form>