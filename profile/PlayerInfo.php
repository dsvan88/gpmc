<? include 'dir_cfg.php';
$def = '<i><нет данных></i>';
if (!$my && $user['email'] !== '')
{
	for ($x=0;$x<strlen($user['email']);$x++)
		if ($x%3===0 || $x%5===0)
			$user['email'][$x]= '*';
}
?>
<div class='InfoRow'><span class='InfoCaption'>Обращение</span>: <span class='InfoValue'><?=$a_genders[(int)$user['gender']]?></span><? if ($my) :?><a class='EditPencil' id='gender'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><?endif?></div>
<div class='InfoRow'><span class='InfoCaption'>Игровое имя</span>: <span class='InfoValue'><?= $user['name'] == '' ? $def : '<b>'.$user['name'].'</b>'?></span></div>
<div class='InfoRow'><span class='InfoCaption'>Статус в клубе</span>:<span class='InfoValue'><?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="span_vote minus t_status">-</span>' : ''),$a_statuses[(int)$user['status']]?><?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="span_vote plus t_status">+</span>' : '')?></span></div>
<div class='InfoRow'><span class='InfoCaption'>Категория</span>:<span class='InfoValue'><?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="span_vote minus">-</span>' : '')?>"<b><i><?=$a_cats[(int)$user['rank']]?></i></b>"<?=(!$my && $user['status'] < $_SESSION['status'] ? '<span class="span_vote plus">+</span>' : '')?></span></div>
<div class='InfoRow'><span class='InfoCaption'>Реальное имя</span>: <span class='InfoValue'><?= $user['fio'] == '' ? $def : '<b>'.$user['fio'].'</b>'?></span><? if ($my) :?><a class='EditPencil' id='fio'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><?endif?></div>
<div class='InfoRow'><span class='InfoCaption'>Дата рождения</span>: <span class='InfoValue'><?= $user['birthday'] === '0' ? $def : '<b>'.date('d.m.Y',$user['birthday']).'</b>'?></span><? if ($my) :?><a class='EditPencil' id='birthday'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><?endif?></div>
<div class='InfoRow'><span class='InfoCaption'>Электронная почта</span>: <span class='InfoValue'><?= $user['email'] == '' ? $def : '<b>'.$user['email'].'</b>'?></span><? if ($my) :?><a class='EditPencil' id='email'><img src = '<?=$settings['img']['edit_pen']['value']?>' title='<?=$settings['img']['edit_pen']['name']?>' alt='<?=$settings['img']['edit_pen']['name']?>'/></a><?endif?></div>