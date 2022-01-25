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


$action->rowDelete(3, SQL_TBLWEEKS);

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


/* $weeksData = $action->getAssocArray(
    $action->query(
        str_replace(
            '{SQL_TBLWEEKS}',
            SQL_TBLWEEKS,
            "SELECT id,start,finish FROM {SQL_TBLWEEKS} ORDER BY id"
        )
    )
);
foreach ($weeksData as $index => $data) {
    $mondayStart = date('H', $data['start']);
    if ($mondayStart > 0) {
        $mondayStart = $data['start'] - (60 * 60 * $mondayStart) + 1;
        $action->rowUpdate(['start' => $mondayStart], ['id' => $data['id']], SQL_TBLWEEKS);
    }
}
 */