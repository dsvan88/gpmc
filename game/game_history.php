<?$enum_winner=array('','Мирные','Мафия','Ничья','Аннулировано');?>
<table id='Game_<?=$game_id?>' class='GameTable'>
	<caption>Игра №<?=++$ti?>. Ведущий: <i><?=$a['manager']?></i>. Рейтинговая игра для ранга: <i><?=$enum_rating[$a['rating']]?></i><br>
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
		<?for($x=0;$x<=$vars['day_count'];$x++):?>
				<td width=30px id='put_<?=$x?>'><?=$players[$i]['puted'][$x] !== -1 ? $players[$i]['puted'][$x] : ''?></td>
		<?endfor;?>
			<td class="prim"><?=($players[$i]['out'] > 0 ? $reasons[$players[$i]['out']] : '')?></td>
		</tr>
	<? endwhile;?>
</table>