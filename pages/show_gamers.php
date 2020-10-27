<?
$max_user_per_page = 10;
$users_count = $engine->GetGamerCount();
$users_on_page = $max_user_per_page * (int) $_GET['page'];
$gamersData = $engine->GetGamerData(['id','name','fio','status','gender','avatar'],'',($users_on_page === 0 ? $max_user_per_page : $users_on_page.','.$max_user_per_page));
?>
<div class="users">
<?
for($x=0; isset($gamersData[$x]); $x++):
	?>
	<div class="users-row">
		<div class="users-row__avatar">
			<a href='/?profile=<?=$gamersData[$x]['id']?>'>
				<?=$engine->checkAndPutImage($gamersData[$x]['avatar'] === '' ? $img_genders[$gamersData[$x]['gender']] : '/gallery/users/'.$gamersData[$x]['id'].'/'.$gamersData[$x]['avatar'],['title'=>$settings['img']['empty_avatar']['name']])?>
			</a>
		</div>
		<div class="users-row__content">
			<h3 class="users-row__content__title">
				<?=$genders[$gamersData[$x]['gender']]?>
				<a href='/?profile=<?=$gamersData[$x]['id']?>'><?=$gamersData[$x]['name']?></a>
			</h3>
			<ul class="users-row__content__data">
				<li><span class="users-row__content__data-label">В миру</span><span class="users-row__content__data-value"><?=$gamersData[$x]['fio']?></span></li>
				<li><span class="users-row__content__data-label">Статус в клубе</span><span class="users-row__content__data-value"><?=$statuses[$gamersData[$x]['status']]?></span></li>
			</ul>
		</div>
	</div>
	<?
endfor;
?>
</div>
<div class="users-pages">
	<?if (isset($_GET['page']) && $_GET['page'] > 0):?>
		<a href="/?trg=gamers&page=<?=$_GET['page']-1?>" class="users-pages__num"><-</a>
	<?endif?>
<?
for ($x=0; $x<$users_count/$max_user_per_page;$x++):
?>
	<a href="/?trg=gamers&page=<?=$x?>" class="users-pages__num <?=($_GET['page'] == $x ? ' active' : '')?>"><?=$x+1?></a>
<?
endfor;
?>
	<?if (!isset($_GET['page']) || isset($_GET['page']) && $_GET['page'] != $x-1):?>
		<a href="/?trg=gamers&page=<?=((int) $_GET['page'])+1?>" class="users-pages__num">-></a>
	<?endif?>
</div>