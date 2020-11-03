<table data-game-id=<?=$game_id?>' class='content__game__table'>
	<caption>
		Игра №<?=$gameData['num']?>. Ведущий: <?=$gameData['manager']?>. Рейтинговая игра для ранга: <i><?=$enum_rating[$gameData['rating']]?></i>&nbsp;(<a data-action-type='stop-game'>Стоп игра!</a>)
	</caption>
	<thead>
		<tr><th rowspan = '2'>№</th><th rowspan = '2'>Игроки:</th><th colspan="4">Фолы:</th><th rowspan = '2'></th><th>Выст.</th><th rowspan = '2'>Прим.</th></tr>
		<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>1</th></tr>
	</thead>
	<tbody>
	<?
	$i=-1;
	while (isset($players[++$i])):?>
		<tr data-player-id="<?=$i?>" data-gamer-id="<?=$players[$i]['id']?>">
			<td class="vote_num"><?=$i+1?>.</td>
			<td class='player-data'>
				<span class='player-data__avatar'>
					<?=$engine->checkAndPutImage($avatar[$players[$i]['id']])?>
				</span>
				<span class='player-data__role'>
					<? if ($players[$i]['role'] > 0) 
						echo $engine->checkAndPutImage('/css/'.$enum_roles[$players[$i]['role']].'.png',['title'=>$enum_roles_rus[$players[$i]['role']]])?>
				</span>
				<span class="player-data__name"><?=$players[$i]['name']?></span>
				<span class='player-data__points'></span>
			</td>
			<?for($x=0;$x<4;$x++):?>
				<td width=30px class='player-data__fouls' data-double-click-action-type="set-player-foul" data-foul-id="<?=$x?>"><?=($x===2 ? '<img src="../css/images/muted.png" style="height:15px;display:none" alt="muted" title="muted">' : '')?></td>
			<?endfor?>
			<td></td>
			<td width=30px class='player-data__puted'></td>
			<td class="player-data__notes"></td>
		</tr>
	<? endwhile;?>
	</tbody>
</table>