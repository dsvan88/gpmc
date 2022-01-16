<?
$output['{NEWS_PREVIEW}'] = '
	<h2 class="news-preview__title section__title">Новини</h2>
	<div class="news-preview__list">';

$page = 0;
if (isset($_GET['news-list-page']))
	$page = (int) $_GET['news-list-page'];

$newsCount = $news->newsGetCount();
$newsAll = $news->newsGetPerPage($page);

$replace = [
	'{NEWS_ITEM_DASHBOARD}' => ''
];
if ($users->checkToken()) {
	$replace['{NEWS_ITEM_DASHBOARD}'] = '
		<span class="news-preview__dashboard">
			<i class="fa fa-pencil-square-o news-dashboard__button" data-action="news-edit-form" data-news-id="{NEWS_ITEM_INDEX}" title="Редагувати новину"></i>
			<i class="fa fa-trash-o news-dashboard__button" data-action="news-delete" data-news-id="{NEWS_ITEM_INDEX}" title="Видалити новину"></i>
		</span>';
}
for ($i = 0; $i < count($newsAll); $i++) {
	$replace['{NEWS_ITEM_LOGO}'] = ($newsAll[$i]['logo'] != '' ? $images->inputImage($newsAll[$i]['logo'], ['title' => 'News logo']) : $images->inputImage($settingsArray['img']['news_default']['value'], ['title' => $settingsArray['img']['news_default']['name']]));
	$replace['{NEWS_ITEM_TITLE}'] = $newsAll[$i]['title'];
	$replace['{NEWS_ITEM_SUBTITLE}'] = $newsAll[$i]['subtitle'];
	$replace['{NEWS_ITEM_HTML}'] = mb_strlen($newsAll[$i]['html'], 'UTF-8') > 150 ? mb_substr(preg_replace('/<[^>]+?>/i', '', $newsAll[$i]['html']), 0, 250) . '...' : $newsAll[$i]['html'];
	$replace['{NEWS_ITEM_INDEX}'] = $newsAll[$i]['id'];
	$output['{NEWS_PREVIEW}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/layouts/item-news.html'));
}
$output['{NEWS_PREVIEW}'] .= '
	</div>';
if ($newsCount > CFG_NEWS_PER_PAGE) {
	$pagesLinks = '';
	$pagesCount = ceil($newsCount / CFG_NEWS_PER_PAGE);
	for ($x = 0; $x < $pagesCount; $x++) {
		$pagesLinks .= "<a href='/?news-list-page=$x'" . ($x == $page ? ' class="active"' : '') . '>' . ($x + 1) . '</a>';
	}
	if ($page > 0) {
		$pagesLinks = '<a href="/?news-list-page=' . ($page - 1) . '"><i class="fa fa-angle-left"></i></a>' . $pagesLinks;
	} else {
		$pagesLinks = '<a><i class="fa fa-angle-left"></i></a>' . $pagesLinks;
	}
	if ($page > 5) {
		$pagesLinks = '<a href="/?news-list-page=0"><i class="fa fa-angle-double-left"></i></a>' . $pagesLinks;
	}


	if ($page != ($pagesCount - 1)) {
		$pagesLinks .= '<a href="/?news-list-page=' . ($page + 1) . '"><i class="fa fa-angle-right"></i></a>';
	} else {
		$pagesLinks .= '<a><i class="fa fa-angle-right"></i></a>';
	}
	if ($pagesCount - 1 - $page > 5) {
		$pagesLinks .= '<a href="/?news-list-page=' . ($pagesCount - 1) . '"><i class="fa fa-angle-double-right"></i></a>';
	}
	$output['{NEWS_PREVIEW}'] .= "<div class='news-preview__links'>$pagesLinks</div>";
}
