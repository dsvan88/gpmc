<div class="timer">
	<div class="timer__header">
		<h3 class='timer__header__title'></h3>
	</div>
	<div class="timer__body">
		<div class='timer__body__watchclock'>01:00:00</div>
		<div class='timer__body__control'>
			<div class='timer__body__control-button disabled' data-action-type="time-control" data-action-mode="undo">
					<?=$engine->inputImage('/css/images/undo.png',['title'=>'Отмена'])?>
			</div>
			<div class='timer__body__control-button' data-action-type="time-control" data-action-mode="start">
				<?=$engine->inputImage('/css/images/start.png',['title'=>'Старт'])?>
			</div>
			<div class='timer__body__control-button' data-action-type="time-control" data-action-mode="reset">
				<?=$engine->inputImage('/css/images/reset.png',['title'=>'Сброс'])?>
			</div>
			<div class='timer__body__control-button' data-action-type="time-control" data-action-mode="next">
				<?=$engine->inputImage('/css/images/next.png',['title'=>'Следующий'])?>
			</div>
		</div>
	</div>
</div>