<?
if ($_GET['p']==='images'):
	$a_settings = $engine->GetSettings(array('id','shname','name','value'), 'img');?>
	<div id='ImgTable'>
	<?for($x=0;$x<count($a_settings);$x++):?>
		<div class='ImgTableCell'>
			<div class='ImgCellTop'><b><?=$a_settings[$x]['shname']?></b></div>
			<div class='ImgPlace' id='<?=$a_settings[$x]['id']?>'>
				<img src='<?=$a_settings[$x]['value']?>'/>
			</div>
			<? $a = getimagesize($_SERVER['DOCUMENT_ROOT'].'/'.$a_settings[$x]['value'])?>
			<span class='ImgCellBot'><?=substr($a_settings[$x]['value'],strrpos($a_settings[$x]['value'],'/')+1)?><br>
			<?=$a[0],'x',$a[1]?></span>
		</div>
	<?endfor?>
	</div>
<?elseif ($_GET['p']==='texts'):
	$a_settings = $engine->GetSettings(array('id','shname','name','value'), 'txt')?>
	<div id='TxtTable'>
		<?for($x=0;$x<count($a_settings);$x++):?>
			<div class='TxtTableCell'>
				<div class='TxtCellTop'>
					<b><?=$a_settings[$x]['shname']?></b>
					<a class='EditPencil' id="<?=$a_settings[$x]['id']?>">
						<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],$settings['img']['edit_pen']['name'])?>
					</a>
				</div>
				<div id='<?=$a_settings[$x]['id']?>' class='TxtCellContent'>
					<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$a_settings[$x]['value'])?>
				</div>
			</div>
		<?endfor?>
	</div>
<?elseif ($_GET['p']==='points'):
	$a_settings = $engine->GetSettings(array('id','name','value'), 'point')?>
	<div id='PntTable'>
		<?for($x=0;$x<count($a_settings);$x++):?>
			<div class='PntTableCell' id="<?=$a_settings[$x]['id']?>">
				<span class='point_name'><b><?=$a_settings[$x]['name']?></b></span><span class="point_value"><?=str_replace(',',', ',$a_settings[$x]['value'])?></span>
				<a class='EditPencil'>
					<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],$settings['img']['edit_pen']['name'])?>
				</a>
			</div>
		<?endfor?>
	</div>
<?elseif ($_GET['p']==='users'):
	$users = $engine->GetPlayerData(array('id','name','rank','last_game','status','username','fio','birthday','gender','email','ar'),'',0);
	$genders=array('-','господин','госпожа','некто');
	$statuses = array('', 'Резидент', 'Основатель');
	$cats = array('C', 'B', 'A');?>
	<table id='UsrTable' style="border-collapse:collapse">
		<?=$engine->MakeTableHeader(['ID'=>0,'Игровое имя'=>0,'Реальное имя'=>0,'Статус в клубе'=>0,'Ранк в клубе'=>0,'Дата рождения'=>0,'Обращение'=>0,'Регистриция'=>0,'Эл. почта'=>0,'Адм. права'=>0,'Последняя игра'=>0,''=>0])?>
		<tbody>
		<?for($x=0;$x<count($users);$x++):?>
			<tr id='<?=$users[$x]['id']?>'>
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
					<a class='EditPencil'>
						<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],$settings['img']['edit_pen']['name'])?>
					</a>
				</td>
			</tr>
		<?endfor?>
		</tbody>
	</table>
<?endif?>
<?//<iframe src="../js/kcfinder/browse.php?type=images" style="width:99%;height:400px;"></iframe>?>
