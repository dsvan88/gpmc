<?
// $output['{SCRIPTS}'] .= '';
$newsAll = $news->newsGetData(['id'=>$_GET['news']]);

$output['{MAIN_CONTENT}'] = "
    <section class='section news'>
        <div class='news__item-logo'>
            ".($newsAll['logo'] != '' ? $images->inputImage($newsAll['logo'],['title'=>'News logo']) : $images->inputImage($settingsArray['img']['news_default']['value'],['title'=>$settingsArray['img']['news_default']['name']]))."
        </span>
        <h3 class='news__item-title'>{$newsAll['title']}</h3>
        <h4 class='news__item-subtitle'>{$newsAll['subtitle']}</h4>
        <div class='news__item-content'>
            {$newsAll['html']}
        </div>
    </section>";
