<div class="admin-panel__news-list">
    <div class="add-news" data-action-mode="add">
        <h3 class="add-news__title">
            Добавление новости
			<span class="span_button" data-action-type="show-add-news-form">
				<?=$engine->checkAndPutImage($settings['img']['plus']['value'])?>
			</span>
			<span class="span_button" data-action-type="apply-news" style="display:none">
				<?=$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']])?>
			</span>
        </h3>
        <div class="add-news__content" style="display:none">
            <form action="/switcher?need=add-news">
                <div class="add-news__content__options">
                    <div class="add-news__content__options-row">
                        <label>Заголовок новости:</label>
                        <input type="text" name="title">
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Подзаголовок новости:</label>
                        <input type="text" name="subtitle">
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Активна до:</label>
                        <input type="text" name="date_remove" class='datepick'>
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Тип новости:</label>
                    <select name="type">
                        <option value="news">Новость</option>
                        <option value="attention">Оповещение</option>
                    </select>
                    </div>
                </div>
                <div class="add-news__content__main-data">
                    <h4>Текст новости</h4>
                    <textarea name="html">
                    </textarea>
                </div>
            </form>
        </div>
    </div>
<?
$max_news_per_page = 5;
$news_count = $engine->GetNewsCount();
$news_on_page = $max_news_per_page * (int) $_GET['page'];
$newsData = $engine->GetNewsData(['id','title','subtitle','logo','html','date_remove'],'',($news_on_page === 0 ? $max_news_per_page : $news_on_page.','.$max_news_per_page));
for($x=0; isset($newsData[$x]); $x++):
    ?>
    <div class="news" data-action-mode="edit" data-news-id="<?=$newsData[$x]['id']?>">
        <h3 class="news__title">
            Редактирование новости
            <span class="span_button" data-action-type="apply-news">
				<?=$engine->checkAndPutImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']])?>
			</span>
        </h3>
        <div class="add-news__content">
            <form action="/switcher?need=add-news">
                <div class="add-news__content__options">
                    <div class="add-news__content__options-row">
                        <label>Заголовок новости:</label>
                        <input type="text" name="title" value="<?=$newsData[$x]['title']?>">
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Подзаголовок новости:</label>
                        <input type="text" name="subtitle" value="<?=$newsData[$x]['subtitle']?>">
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Активна до:</label>
                        <input type="text" name="date_remove" class='datepick' value="<?=date('d.m.Y H:i:s', $newsData[$x]['date_remove'])?>">
                    </div>
                    <div class="add-news__content__options-row">
                        <label>Тип новости:</label>
                    <select name="type">
                        <option value="news"<?=($newsData[$x]['title'] === 'news' ? ' selected' : '')?>>Новость</option>
                        <option value="attention"<?=($newsData[$x]['title'] === 'attention' ? ' selected' : '')?>>Оповещение</option>
                    </select>
                    </div>
                </div>
                <div class="add-news__content__main-data">
                    <h4>Текст новости</h4>
                    <textarea name="html" class='news'><?=$newsData[$x]['html']?></textarea>
                </div>
            </form>
        </div>
    </div>
<? endfor ?>
</div>