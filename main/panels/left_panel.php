<div id='LeftPanel'>
	<div class='l_button' id='EndedGames'><a href='/?trg=history'><img src='<?=$settings['img']['history']['value']?>' alt="<?=$settings['img']['history']['name']?>" title="<?=$settings['img']['history']['name']?>"/><span>Прошлые игры</span></a></div>
	<div class='l_button' id='RatingGamers'><a href='/?trg=rating'><img src='<?=$settings['img']['rating']['value']?>' alt="<?=$settings['img']['rating']['name']?>" title="<?=$settings['img']['rating']['name']?>"/><span>Рейтинг</span></a></div>
	<?if (isset($_SESSION['id'])):?>
		<div class='l_button'><a href='<?='/?profile=',$_SESSION['id']?>'><img src='<?=$img_genders[$_SESSION['gender']]?>'/><span>Профиль</span></a></div>
	<?endif;
	if (isset($_SESSION['status']) && $_SESSION['status'] > 0):
		$votings = $engine->GetUnvotedVotings();
		?>
		<div class='l_button'><a href='/?trg=voting'><img src='<?=$settings['img']['voting']['value']?>' alt="<?=$settings['img']['voting']['name']?>" title="<?=$settings['img']['voting']['name']?>"/><span>Голосование</span></a><? if ($votings[0] != 0) : ?><div class='NewAlert'><?=$votings[0]?></div><?endif?></div>
	<?endif?>
	<div class='l_button'><a href='/?trg=about'><img src='<?=$settings['img']['about']['value']?>' alt="<?=$settings['img']['about']['name']?>" title="<?=$settings['img']['about']['name']?>"/><span>О нас</span></a></div>
</div>