<?php
$output['message'] = "<i>Инструкция к боту</i>.\r\n
<b>Команды</b>:\r\n
    + (день недели) <i>//зарегистрироваться на запланированные игры текущей недели, примеры:</i>
+вс
+вт на 19:30 думаю, что отсижу 1-2 игры
+ пн на 1-2 игры
+ на сегодня, буду к 18:30

    - (день недели) <i>//отписаться от игр в определённый день, на который ранее записались, примеры:</i>
-вс
-понедельник
- завтра

    <u>/week</u> <i>// Расписание ближайших игр на неделе</i>
    <u>/nick Ваш псевдоним</u> (кириллицей) <i>// Зарегистрировать свой псевдоним</i>
    <u>/?</u> или <u>/help</u> <i>// Это меню</i>
";
if ($_POST['message']['chat']['type'] === 'private') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

    $users = new Users;

    $telegramId = $_POST['message']['from']['id'];

    $userData = $users->usersGetData(['id', 'status'], ['telegramid' => $telegramId]);
    if (in_array($userData['status'], ['admin', 'manager'])) {
        $output['message'] .= "\r\nКоманды админа:
    /reg <i>// зарегистрировать игрока на указанный день, пример:</i>
/reg +вс, Псевдоним, 18:00, 1-2 игры
    
    /recall <i>// отменить сбор. Восстанавливается новой регистрацией от админа. Можно указать день недели.</i>

    /promo <i>// Зафиксировать какое-то оповещение, что добавляется внизу команды /week.</i>
    Текст до первого переноса строки - заголовок, до второго - подзаголовок, всё что ниже - текст оповещения. Пример:
/promo Заголовок
Подзголовок
Или - тут могла быть, Ваша Реклама :)
";
    }
}
