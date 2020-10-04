<?$users = $engine->GetGamerData(array('id','name','rank','last_game','status','username','fio','birthday','gender','email','ar'),'',0);
	$genders=array('-','господин','госпожа','некто');
	$statuses = array('', 'Резидент', 'Основатель');
	$cats = array('C', 'B', 'A');?>
	<table class='users-table' style="border-collapse:collapse">
		<?=$engine->MakeTableHeader(['ID'=>0,'Игровое имя'=>0,'Реальное имя'=>0,'Статус в клубе'=>0,'Ранк в клубе'=>0,'Дата рождения'=>0,'Обращение'=>0,'Регистриция'=>0,'Эл. почта'=>0,'Адм. права'=>0,'Последняя игра'=>0,''=>0])?>
		<tbody>
		<?for($x=0;$x<count($users);$x++):?>
			<tr data-user-id='<?=$users[$x]['id']?>'>
				<td><?=$users[$x]['id']?>.</td>
				<td><?=$users[$x]['name']?></td>
				<td><?=$users[$x]['fio']?></td>
				<td><?=$statuses[$users[$x]['status']]?></td>
				<td><?=$cats[$users[$x]['rank']]?></td>
				<td><?=($users[$x]['birthday'] > 0 ? date('d.m.Y',$users[$x]['birthday']) : '')?></td>
				<td><?=$genders[$users[$x]['gender']]?></td>
				<td><?=($users[$x]['username'] != '' ? '+' : '')?></td>
				<td><?=$users[$x]['email']?></td>
				<td><?=($users[$x]['ar'] > 0 ? '+' : '')?></td>
				<td><?=$users[$x]['last_game']?></td>
				<td>
					<a data-action-type="edit-user-row" title="Изменить" alt="Изменить">
						<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
					</a>
				</td>
			</tr>
		<?endfor?>
		</tbody>
	</table>