<?
if (!isset($_GET['nid'])){
	$max_news_per_page = 10;
	$news_count = 10;
	// $news_count = $engine->GetNewsCount();
	$news_on_page = $max_news_per_page * (int) $_GET['page'];
	// $newsData = $engine->GetNewsData(['id','title','subtitle','html','date_add','date_remove'],['type'=>'news'],($news_on_page === 0 ? $max_news_per_page : $news_on_page.','.$max_news_per_page));
	?>
	<div class="news-list">
	<?
	/*for($x=0; isset($newsData[$x]); $x++):
		?>
		<div class="news-list-row">
			<div class="news-list-row__avatar">
				<a href='/?profile=<?=$newsData[$x]['id']?>'>
					<?=$engine->checkAndPutImage($newsData[$x]['avatar'] === '' ? $img_genders[$newsData[$x]['gender']] : '/gallery/users/'.$newsData[$x]['id'].'/'.$newsData[$x]['avatar'],['title'=>$settings['img']['empty_avatar']['name']])?>
				</a>
			</div>
			<div class="news-list-row__content">
				<h3 class="news-list-row__content__title">
					<?=$genders[$newsData[$x]['gender']]?>
					<a href='/?profile=<?=$newsData[$x]['id']?>'><?=$newsData[$x]['name']?></a>
				</h3>
				<ul class="news-list-row__content__data">
					<li><span class="news-list-row__content__data-label">В миру</span><span class="news-list-row__content__data-value"><?=$newsData[$x]['fio']?></span></li>
					<li><span class="news-list-row__content__data-label">Статус в клубе</span><span class="news-list-row__content__data-value"><?=$statuses[$newsData[$x]['status']]?></span></li>
				</ul>
			</div>
		</div>
		<?
	endfor;*/
	?>
	</div>
	<div class="news-list-pages">
		<?if (isset($_GET['page']) && $_GET['page'] > 0):?>
			<a href="/?trg=news&page=<?=$_GET['page']-1?>" class="news-list-pages__num"><-</a>
		<?endif?>
	<?
	for ($x=0; $x<$news_count/$max_news_per_page;$x++):
	?>
		<a href="/?trg=news&page=<?=$x?>" class="news-list-pages__num <?=($_GET['page'] == $x ? ' active' : '')?>"><?=$x+1?></a>
	<?
	endfor;
	?>
		<?if (!isset($_GET['page']) || isset($_GET['page']) && $_GET['page'] != $x-1):?>
			<a href="/?trg=news&page=<?=((int) $_GET['page'])+1?>" class="news-list-pages__num">-></a>
		<?endif?>
	</div>
<?
}
else{
	?> Not ready yet!<?
}
?>