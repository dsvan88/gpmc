<aside class='left-panel'>
	<div class='left-panel__button' id='EndedGames'>
		<a href='/?trg=history'>
			<?=$engine->checkAndPutImage($settings['img']['history']['value'],['title'=>$settings['img']['history']['name']])?>
			<span>Прошлые игры</span>
		</a>
	</div>
	<div class='left-panel__button' id='RatingGamers'>
		<a href='/?trg=rating'>
			<?=$engine->checkAndPutImage($settings['img']['rating']['value'],['title'=>$settings['img']['rating']['name']])?>
			<span>Рейтинг</span>
		</a>	
	</div>
	<?if (isset($_SESSION['id'])):?>
		<div class='left-panel__button'>
			<a href='<?='/?profile=',$_SESSION['id']?>'>
				<?=$engine->checkAndPutImage($img_genders[$_SESSION['gender']],['title'=>'Профиль'])?>
				<span>Профиль</span>
			</a>
		</div>
	<?endif;
	if (isset($_SESSION['status']) && $_SESSION['status'] > 0):
		$votings = $engine->GetUnvotedVotings();
		?>
		<div class='left-panel__button'>
			<a href='/?trg=voting'><?=$engine->checkAndPutImage($settings['img']['voting']['value'],['title'=>$settings['img']['voting']['name']])?><span>Голосование</span></a>
			<? if ($votings[0] != 0) : ?><div class='NewAlert'><?=$votings[0]?></div><?endif?></div>
	<?endif?>
	<div class='left-panel__button'>
		<a href='/?trg=about'>
			<?=$engine->checkAndPutImage($settings['img']['about']['value'],['title'=>$settings['img']['about']['name']])?>
			<span>О нас</span>
		</a>
	</div>
</aside>