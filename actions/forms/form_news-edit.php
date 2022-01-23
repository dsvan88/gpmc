<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.news.php';

if (isset($_POST['newsType'])) {
    $news = new News();
    $newsData = $news->newsGetAllByType($_POST['newsType']);
    if ($newsData) {
        $newsData = $newsData[0];
        $newsId = (int) $newsData['id'];
    } else {
        $newsData = [
            'title' => '',
            'subtitle' => '',
            'html' => '',
            'type' => 'promo'
        ];
        $newsId = -1;
    }
} else {
    $newsId = (int) $_POST['newsId'];
    $news = new News();
    $newsData = $news->newsGetData($newsId);
}
$replace = [
    '{NEWS_ID}' => $newsId,
    '{NEWS_TITLE}' => $newsData['title'],
    '{NEWS_SUBTITLE}' => $newsData['subtitle'],
    '{NEWS_HTML}' => $newsData['html'],
    '{NEWS_TYPE_NEWS}' => $newsData['type'] === 'news' ? ' selected ' : '',
    '{NEWS_TYPE_ATTENTION}' => $newsData['type'] === 'attention' ? ' selected ' : '',
    '{NEWS_TYPE_TG_INFO}' => $newsData['type'] === 'tg-info' ? ' selected ' : '',
    '{NEWS_TYPE_PROMO}' => $newsData['type'] === 'promo' ? ' selected ' : ''
];

$output['html'] = str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/forms/form_news-edit.html'));
