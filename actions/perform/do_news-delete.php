<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.news.php';

$news = new News();
$result = $news->newsDelete($_POST['newsId']);
$output['text'] = 'Новина успішно видалена';