<?
require __DIR__ . '/class.action.php';

$action = new Action();

$action->query(
    str_replace(
        '{SQL_TBLUSERS}',
        SQL_TBLUSERS,
        "ALTER TABLE {SQL_TBLUSERS}
			ADD COLUMN telegramId CHARACTER VARYING(50) NOT NULL DEFAULT ''
    "
    )
);
