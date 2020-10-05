<? 
$def = '<i><нет данных></i>';
if (!$my && $user['email'] !== '')
{
	for ($x=0;$x<strlen($user['email']);$x++)
		if ($x%3===0 || $x%5===0)
			$user['email'][$x]= '*';
}
?>
<div class='info-row'>
	<span class='info-row__title'>Обращение</span>
	<span class='info-row__value'>
		<?=$a_genders[(int)$user['gender']]?>
	</span>
	<? if ($my) :?>
		<a class='info-row__edit' data-form-type="edit-my-info" data-edit-row='gender'>
			<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
		</a>
	<?endif?>
</div>
<div class='info-row'>
	<span class='info-row__title'>Игровое имя</span>
	<span class='info-row__value'>
		<?= $user['name'] == '' ? $def : '<b>'.$user['name'].'</b>'?>
	</span>
</div>
<div class='info-row'>
	<span class='info-row__title'>Статус в клубе</span>
	<span class='info-row__value'>
		<?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="info-row__value__vote minus" data-action-type="start-new-vote" data-vote-type="status">-</span>' : ''),$a_statuses[(int)$user['status']]?><?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="info-row__value__vote plus" data-action-type="start-new-vote" data-vote-type="status">+</span>' : '')?>
	</span>
</div>
<div class='info-row'>
	<span class='info-row__title'>Категория</span>
	<span class='info-row__value'>
		<?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="info-row__value__vote minus" data-action-type="start-new-vote" data-vote-type="rank">-</span>' : '')?>"<b><i><?=$a_cats[(int)$user['rank']]?></i></b>"<?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="info-row__value__vote plus" data-action-type="start-new-vote" data-vote-type="status">+</span>' : '')?>
	</span>
</div>
<div class='info-row'>
	<span class='info-row__title'>Реальное имя</span>
	<span class='info-row__value'>
		<?= $user['fio'] == '' ? $def : '<b>'.$user['fio'].'</b>'?>
	</span>
	<? if ($my) :?>
		<a class='info-row__edit' data-form-type="edit-my-info" data-edit-row='fio'>
			<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
		</a>
	<?endif?>
</div>
<div class='info-row'>
	<span class='info-row__title'>Дата рождения</span>
	<span class='info-row__value'>
		<?= $user['birthday'] === '0' ? $def : '<b>'.date('d.m.Y',$user['birthday']).'</b>'?>
	</span>
	<? if ($my) :?>
		<a class='info-row__edit' data-form-type="edit-my-info" data-edit-row='birthday'>
			<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
		</a>
	<?endif?>
</div>
<div class='info-row'>
	<span class='info-row__title'>Электронная почта</span>
	<span class='info-row__value'>
		<?= $user['email'] == '' ? $def : '<b>'.$user['email'].'</b>'?>
	</span>
	<? if ($my) :?>
		<a class='info-row__edit' data-form-type="edit-my-info" data-edit-row='email'>
			<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
		</a>
	<?endif?>
</div>