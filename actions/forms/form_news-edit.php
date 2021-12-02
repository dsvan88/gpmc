<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.news.php';

$news = new News();

$newsData = $news->newsGetData($_POST['newsId']);

$replace = [
    '{NEWS_ID}' => $_POST['newsId'],
    '{NEWS_TITLE}' => $newsData['title'],
    '{NEWS_SUBTITLE}' => $newsData['subtitle'],
    '{NEWS_HTML}' => $newsData['html'],
    '{NEWS_TYPE_NEWS}' => $newsData['type'] === 'news' ? ' selected ' : '',
    '{NEWS_TYPE_ATTENTION}' => $newsData['type'] === 'attention' ? ' selected ' : '',
    '{NEWS_TYPE_TG_INFO}' => $newsData['type'] === 'tg-info' ? ' selected ' : ''
];

$output['html'] = str_replace(array_keys($replace),array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_news-edit.html'));