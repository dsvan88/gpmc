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
print_r($weeksData);
