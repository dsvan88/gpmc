<table id='Game_<?=$game_id?>' class='content__game__table'>
	<caption>
		Игра №<?=$a['num']?>. Ведущий: <?=$a['manager']?>. Рейтинговая игра для ранга: <i><?=$enum_rating[$a['rating']]?></i>&nbsp;(<a id='StopGame'>Стоп игра!</a>)
	</caption>
	<thead><tr><th rowspan = '2'>№</th><th rowspan = '2'>Игроки:</th><th colspan="4">Фолы:</th><th rowspan = '2'></th><th>Выст.</th><th rowspan = '2'>Прим.</th></tr>
	<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>1</th></tr>
	</thead>
	<tbody>
	<?
	$i=-1;
	while (isset($players[++$i])):?>
		<tr id="<?=$i?>_<?=$players[$i]['id']?>">
			<td class="vote_num"><?=$i+1?>.</td>
			<td class='player_name'>
				<span class='player_avatar'>
					<?=$engine->checkAndPutImage($avatar[$players[$i]['id']])?>
				</span>
				<span class='for_image hide'>
					<? if ($players[$i]['role'] > 0) 
						echo $engine->checkAndPutImage('/css/'.$enum_roles[$players[$i]['role']].'.png',['title'=>$enum_roles_rus[$players[$i]['role']]])?>
				</span>
				<?=$players[$i]['name']?>
				<span class='points'></span>
			</td>
		<?for($x=0;$x<4;$x++):?>
			<td width=30px id='foul_<?=$x+1?>' class='foul'><?=($x===2 ? '<img src="../css/images/muted.png" style="height:15px;display:none" alt="muted" title="muted">' : '')?></td>
		<?endfor?>
		<td></td>
		<td width=30px class='puted' id='put_0'></td>
		<td class="prim"></td>
		</tr>
	<? endwhile;?>
	</tbody>
</table>