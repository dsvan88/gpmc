<div class="timer">
	<div class="timer__header">
		<h3 class='timer__header__title'>Фаза ночи.<br>Минута договора игроков мафии.<br>Шериф может взглянуть на город.</h3>
	</div>
	<div class="timer__body">
		<div class='timer__body__watchclock'>01:00:00</div>
		<div class='timer__body__control'>
			<div class='timer__body__control__undo disabled'>
					<?=$engine->checkAndPutImage('/css/images/undo.png','Отмена')?>
			</div>
			<div class='timer__body__control__start'>
				<?=$engine->checkAndPutImage('/css/images/start.png','Старт')?>
			</div>
			<div class='timer__body__control__reset'>
				<?=$engine->checkAndPutImage('/css/images/reset.png','Сброс')?>
			</div>
			<div class='timer__body__control__next'>
				<?=$engine->checkAndPutImage('/css/images/next.png','Следующий')?>
			</div>
		</div>
	</div>
</div>