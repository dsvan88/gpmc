<div class="news">
	<h2 class='page-title'>Новости клуба <?=MAFCLUB_NAME?></h2>
<?
if (!isset($_GET['nid'])){
	$max_news_per_page = 10;
	$news_count = $engine->GetNewsCount();
	if ($news_count > 0):
		$news_on_page = $max_news_per_page * (int) $_GET['page'];
		$newsData = $engine->GetNewsData(['id','title','subtitle','logo','html','date_add','date_remove'],['type'=>'news'],($news_on_page === 0 ? $max_news_per_page : $news_on_page.','.$max_news_per_page));
	
		?>
		<div class="news-list">
		<?
		for($x=0; isset($newsData[$x]); $x++):
			?>
			<div class="news-list-row">
				<div class="news-list-row__logo">
					<a href='/?trg=news&nid=<?=$newsData[$x]['id']?>'>
						<?=$engine->checkAndPutImage($newsData[$x]['logo'] === '' ? $settings['img']['news_default']['value'] : $newsData[$x]['logo'],['title'=>$settings['img']['news_default']['name']])?>
					</a>
				</div>
				<div class="news-list-row__content">
					<h3 class="news-list-row__content__title">
						<a href='/?trg=news&nid=<?=$newsData[$x]['id']?>'><?=$newsData[$x]['title']?></a>
					</h3>
					<div class="news-list-row__content__subtitle"><?=$newsData[$x]['subtitle']?></div>
					<div class="news-list-row__content__text"><?=mb_substr($newsData[$x]['html'],0,200,'UTF-8')?>…</div>
					<div class="news-list-row__content__read-more">
						<a href='/?trg=news&nid=<?=$newsData[$x]['id']?>'>Читать дальше…</a>
					</div>
				</div>
			</div>
			<?
		endfor;
		?>
		</div>
		<?
		if ($news_count > $max_news_per_page):
			?>
			<div class="list-pages">
				<?if (isset($_GET['page']) && $_GET['page'] > 0):?>
					<a href="/?trg=news&page=<?=$_GET['page']-1?>" class="list-pages__num"><-</a>
				<?endif?>
			<?
			for ($x=0; $x<$news_count/$max_news_per_page;$x++):
			?>
				<a href="/?trg=news&page=<?=$x?>" class="list-pages__num <?=($_GET['page'] == $x ? ' active' : '')?>"><?=$x+1?></a>
			<?
			endfor;
			?>
				<?if (!isset($_GET['page']) || isset($_GET['page']) && $_GET['page'] != $x-1):?>
					<a href="/?trg=news&page=<?=((int) $_GET['page'])+1?>" class="list-pages__num">-></a>
				<?endif?>
			</div>
		<?endif?>
	<?else:?>
	<h2><i>Актуальных новостей - пока нет. Загляните к нам позднее!</i></h2>
	<?endif?>
<?
}
else{
	$newsData = $engine->GetNewsData(['id','title','subtitle','logo','html','date_add','date_remove'],['type'=>'news','id'=>$_GET['nid']],1);
	?>
	<h3 class="news__title"><?=$newsData['title']?></h3>
	<p class="news__subtitle"><?=$newsData['subtitle']?></p>
	<div class="news__content">
		<?=$engine->checkAndPutImage($newsData['logo'] === '' ? $settings['img']['news_default']['value'] : $newsData['logo'],['title'=>$settings['img']['news_default']['name'], 'class' => 'news__content__logo'])?>
		<?=str_replace('!BR!', '</br>',$newsData['html'])?>
	</div>
<?
}
?>
</div>


