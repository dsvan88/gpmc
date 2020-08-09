<? 
if (!isset($EveningData))
	$EveningID = $engine->SetEveningID(strtotime(isset($_POST['eve_date']) ? $_POST['eve_date'] : date('d.m.Y')),$players['ids']);
else 
	$EveningID = $EveningData['id'];
$players = $engine->GetRandomPlayers(10,$EveningID);
?>
<script>EveningID=<?=$EveningID?></script>
<? require $root_path.'/game/game_register_form.php'?>
<br>
<div class='SepDiv'>
	<button id="ShufleGamers" class="menu_button">Перемешать игроков</button>
</div>
<br>
<hr/>
<? require $root_path.'/evening/evening_player_list.php'?>
<hr/>
<div class='SepDiv'><button id='AddPlayersToArray'>Добавить игрока</button></div>
<? require $root_path.'/game/check_game_in_progress.php';