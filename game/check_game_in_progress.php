<? if (isset($_SESSION['id_game']) && $_SESSION['id_game'] > 0 && $engine->GameExists($_SESSION['id_game']) || $engine->TrygameResume($EveningID)): ?>
	<hr/>
	<div class='evening-active__buttons'>
		<span class='span_button' data-action-type='resume-game' data-game-id='<?=$_SESSION['id_game']?>'>
			<?=$engine->inputImage($settings['img']['apply']['value'])?>
			Возобновить игру
			<?=$engine->inputImage($settings['img']['apply']['value'])?>
		</span>
	</div>
<?endif?>