<?
require __DIR__ . '/class.action.php';

$action = new Action();
/* 
$action->query(
    str_replace(
        '{SQL_TBLUSERS}',
        SQL_TBLUSERS,
        "ALTER TABLE {SQL_TBLUSERS}
			ADD COLUMN telegramId CHARACTER VARYING(50) NOT NULL DEFAULT ''
    "
    )
); */

// Обработка данных недели - добавление/удаление каких-то данных из недели
/* $weeksData = $action->getAssocArray(
    $action->query(
        str_replace(
            '{SQL_TBLWEEKS}',
            SQL_TBLWEEKS,
            "SELECT id,data,start,finish FROM {SQL_TBLWEEKS}"
        )
    )
);

foreach ($weeksData as $weekData) {
    $weekData['data'] = json_decode($weekData['data'], true);
    foreach ($weekData['data'] as $index => $data) {
        if (isset($weekData['data'][$index]['status'])) continue;
        $weekData['data'][$index]['status'] = '';
    }
    $weekData['data'] = json_encode($weekData['data'], JSON_UNESCAPED_UNICODE);

    $weekId = $weekData['id'];
    unset($weekData['id']);
    print_r($weekData);
    echo '<br>';

    $action->rowUpdate($weekData, ['id' => $weekId], SQL_TBLWEEKS);
} */


// Удалить неделю и сбросить идентификаторы недель
$action->rowDelete(4, SQL_TBLWEEKS);
$action->rowDelete(5, SQL_TBLWEEKS);

$weeksData = $action->getAssocArray(
    $action->query(
        str_replace(
            '{SQL_TBLWEEKS}',
            SQL_TBLWEEKS,
            "SELECT data,start,finish FROM {SQL_TBLWEEKS} ORDER BY id"
        )
    )
);

$action->query(
    str_replace(
        '{SQL_TBLWEEKS}',
        SQL_TBLWEEKS,
        "TRUNCATE {SQL_TBLWEEKS} RESTART IDENTITY"
    )
);
$action->rowInsert($weeksData, SQL_TBLWEEKS);

$weeksData = $action->getAssocArray(
    $action->query(
        str_replace(
            '{SQL_TBLWEEKS}',
            SQL_TBLWEEKS,
            "SELECT data,start,finish FROM {SQL_TBLWEEKS} ORDER BY id"
        )
    )
);
