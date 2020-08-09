<? if (isset($_SESSION['id_game']) && $_SESSION['id_game'] > 0 && $engine->GameExists($_SESSION['id_game']) || $engine->TryResumeGame($EveningID)): ?>
	<hr/>
	<div class='SepDiv'><button id='ResumeGame' value='<?=$_SESSION['id_game']?>'>Возобновить игру</button></div>
<?endif?>