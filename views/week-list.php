<?
$output['{WEEK_LIST}'] = '
	<h2 class="week-preview__title section__title">Розклад ігор</h2>
	<div class="week-preview__list">';

for ($i = 0; $i < 7; $i++) {
	$replace['{DAY_DATE}'] = date('d.m.Y');
	$replace['{DAY_GAME}'] = 'Мафия';
	$replace['{DAY_PARTICIPANTS}'] = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, ut.';
	$replace['{DAY_READMORE}'] = '';
	$replace['{WEEK_INDEX}'] = 0;
	$replace['{DAY_INDEX}'] = $i;

	$output['{WEEK_LIST}'] .= str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/day-item.html'));
}
$output['{WEEK_LIST}'] .= '
	</div>';
/* if ($newsCount > CFG_NEWS_PER_PAGE) {
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
	$output['{WEEK_LIST}'] .= "<div class='news-preview__links'>$pagesLinks</div>";
}
 */