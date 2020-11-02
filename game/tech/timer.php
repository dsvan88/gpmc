<div class="timer">
	<div class="timer__header">
		<h3 class='timer__header__title'></h3>
	</div>
	<div class="timer__body">
		<div class='timer__body__watchclock'>01:00:00</div>
		<div class='timer__body__control'>
			<div class='timer__body__control__undo disabled'>
					<?=$engine->checkAndPutImage('/css/images/undo.png',['title'=>'Отмена'])?>
			</div>
			<div class='timer__body__control__start'>
				<?=$engine->checkAndPutImage('/css/images/start.png',['title'=>'Старт'])?>
			</div>
			<div class='timer__body__control__reset'>
				<?=$engine->checkAndPutImage('/css/images/reset.png',['title'=>'Сброс'])?>
			</div>
			<div class='timer__body__control__next'>
				<?=$engine->checkAndPutImage('/css/images/next.png',['title'=>'Следующий'])?>
			</div>
		</div>
	</div>
</div>