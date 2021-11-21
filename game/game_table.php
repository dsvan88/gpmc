<? if (isset($vars)) :?>
	<table id='Game_<?=$game_id?>' class='GameTable'>
	<caption>Игра №<?=++$ti?>. Ведущий: <i><?=$engine->getGamerName($game['manager'])?></i>. Рейтинговая игра для ранга: <i><?=$enum_rating[$a['rating']]?></i><br>
	Победитель: <i><?=$enum_winner[$vars['win']]?></i></caption>
	<thead><tr><th rowspan = '2'>№</th><th rowspan = '2'>Игроки:</th><th colspan="4">Фолы:</th><th rowspan = '2'></th><th colspan='<?=$vars['day_count']+1?>'>Выставил:</th><th rowspan = '2'>Прим.</th></tr>
	<tr><th>1</th><th>2</th><th>3</th><th>4</th><?for($x=0;$x<=$vars['day_count'];++$x){ ?><th><?=$x?></th><? }?></tr>
	</thead>
	<?
	$i=-1;
	while (isset($players[++$i])):?>
		<tr id="<?=$i?>_<?=$players[$i]['id']?>" <?=$players[$i]['out'] > 0 ? 'class="out"' : '' ?>>
			<td class="vote_num"><?=$i+1?>.</td>
			<td class='player_name'><div class='player_avatar'><img src="<?=$avatar[$players[$i]['id']]?>"/></div><div class='for_image'><?=$players[$i]['role'] > 0 ? '<img src="../css/'.$enum_roles[$players[$i]['role']].'.png" style="height: 25px;" alt="'.$enum_roles_rus[$players[$i]['role']].'" title="'.$enum_roles_rus[$players[$i]['role']].'">' : '';?></div><?=$players[$i]['name']?><span class='points <?=$players[$i]['points'] > 0.0 ? 'positive' : 'negative'?>'><?=$players[$i]['points']?></span></td>
		<?for($x=0;$x<4;$x++):?>
			<td width=30px id='foul_<?=$x+1?>' class='foul <?=($players[$i]['fouls'] > $x ? 'fail' : '')?>'></td>
		<?endfor?>
		<td></td>
		<?	for($x=0;$x<=$vars['day_count'];$x++):?>
				<td width=30px id='put_<?=$x?>'><?=$players[$i]['puted'][$x] !== -1 ? $players[$i]['puted'][$x] : ''?></td>
		<?endfor;?>
			<td class="prim"><?=($players[$i]['out'] > 0 ? $reasons[$players[$i]['out']] : '')?></td>
		</tr>
	<? endwhile;?>
	</table>
<? else: ?>
	<table id='Game_<?=$game_id?>' class='GameTable'>
	<caption>Игра №<?=$a['num']?>. Ведущий: <?=$a['manager']?>. Рейтинговая игра для ранга: <i><?=$enum_rating[$a['rating']]?></i>&nbsp;(<a id='StopGame'>Стоп игра!</a>)</caption>
	<thead><tr><th rowspan = '2'>№</th><th rowspan = '2'>Игроки:</th><th colspan="4">Фолы:</th><th rowspan = '2'></th><th>Выст.</th><th rowspan = '2'>Прим.</th></tr>
	<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>1</th></tr>
	</thead>
	<?
	$i=-1;
	while (isset($players[++$i])):?>
		<tr id="<?=$i?>_<?=$players[$i]['id']?>">
			<td class="vote_num"><?=$i+1?>.</td>
			<td class='player_name'><div class='player_avatar'><img src="<?=$avatar[$players[$i]['id']]?>"/></div><div class='for_image hide'><?=$players[$i]['role'] > 0 ? '<img src="../css/'.$enum_roles[$players[$i]['role']].'.png" style="height:25px;" alt="'.$enum_roles_rus[$players[$i]['role']].'" title="'.$enum_roles_rus[$players[$i]['role']].'">' : ''?></div><?=$players[$i]['name']?><span class='points'></span></td>
		<?for($x=0;$x<4;$x++):?>
			<td width=30px id='foul_<?=$x+1?>' class='foul'><?=($x===2 ? '<img src="../css/images/muted.png" style="height:15px;display:none" alt="muted" title="muted">' : '')?></td>
		<?endfor?>
		<td></td>
		<td width=30px class='puted' id='put_0'></td>
		<td class="prim"></td>
		</tr>
	<? endwhile;?>
	</table>
<?endif;?>
<span id="OnVote" class="ingame_event hide">На голосовании игроки под номерами:</span>
<div id="ShowLog_<?=$game_id?>" class="LogHeader">+ Открыть лог игры</div>
<div id="Log_<?=$game_id?>" class='hide'><?=str_replace(array('BR','HR'),array(' ','<hr>'),$engine->gameLogGet($game_id))?></div>
<div id="best_move" class = "ingame_event<?=$bm!='' ? '' : ' hide'?>">
	<span class="ingame_event">Лучший ход:&nbsp;</span><span id='bm' class="ingame_event">&nbsp;<?=$bm?></span><br>
</div>