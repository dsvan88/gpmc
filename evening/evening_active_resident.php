<?
if (!isset($EveningData))
	$EveningID = $engine->SetEveningID(strtotime(isset($_POST['eve_date']) ? $_POST['eve_date'] : date('d.m.Y')),$players['ids']);
else 
	$EveningID = $EveningData['id'];

$players = $engine->GetRandomPlayers(10,$EveningID);
?>
<div class="evening-active" data-evening-id="<?=$EveningID?>">
	<? require $root_path.'/game/game_register_form.php'?>

	<div class='evening-active__buttons'>
		<span class='span_button' data-action-type='shuffle-gamers'>
			<!-- <?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?> -->
			Перемешать игроков
			<!-- <?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?> -->
		</span>
	</div>
	<!-- <button id="ShufleGamers" class="menu_button">Перемешать игроков</button> -->
	<hr/>
	<? require $root_path.'/evening/evening_gamers_list.php'?>
	<hr/>
	<div class='evening-active__buttons'>
		<span class='span_button' data-form-type='add-players-to-array'>
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?>
			Добавить игрока
			<?=$engine->checkAndPutImage($settings['img']['plus']['value'],'')?>
		</span>
	</div>
	<!-- <div class='SepDiv'><button id='AddPlayersToArray'>Добавить игрока</button></div> -->
	<? require $root_path.'/game/check_game_in_progress.php';?>
</div>