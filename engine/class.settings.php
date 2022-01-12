<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.action.php';

class Settings
{
	private $action;
	function __construct()
	{
		$this->action = $GLOBALS['CommonActionObject'];
	}
	// Работа с настройками сайта:
	// $columns - какие колонки выбрать из таблицы настройками
	// $type - тип настройки: pages - Html код страниц сайта, txt - тексты сайта (типа приветствия, ещё какой-то лабуды), img - пути к основным картинкам сайта (лого, история, приветствия)
	// $by - настройки выборки - ассоциативный массив, где ключ - имя колонки для выборки, значение - её значение, для выборки
	function settingsGet($columns, $type = 'pages', $by = '')
	{
		$columns = is_array($columns) ? implode(',', $columns) : $columns;
		$table = SQL_TBLSETTINGS;
		$values = [];
		if (is_array($type)) {
			$conditions = 'type IN (' . substr(str_repeat('?,', count($type)), 0, -1) . ')';
			$values = array_merge($values, $type);
		} else {
			$conditions = 'type = ?';
			$values[] = $type;
		}

		if (is_array($by)) {
			$key = array_keys($by)[0];
			$conditions .= " AND $key = ? ";
			$values[] = $by[$key];
		}

		if ($r = $this->action->prepQuery("SELECT $columns FROM $table WHERE $conditions", $values))
			return $this->action->getAssocArray($r);
		else return false;
	}
	// Работа с настройками сайта:
	// pages - Html код страниц сайта;
	// txt - тексты сайта (типа приветствия, футера, ещё какой-то лабуды)
	// img - пути к основным картинкам сайта (лого, мб добавим что-то ещё позднее)
	// point - дополнительные баллы игрокам за какие-либо действия в игре
	// tg-bot - токен телеграм бота, куда слать сообщения
	// tg-chat - id основного чата
	// tg-pinned - последнее закреплённое сообщение в основной группе
	function settingsSet($data, $id = 'add')
	{
		if ($id !== 'add') {
			$this->action->rowUpdate($data, ['id' => $id], SQL_TBLSETTINGS);
			return $id;
		}
		return $this->action->rowInsert($data, SQL_TBLSETTINGS);
	}
	// Изменение массива настроек для более удобного применения.
	function modifySettingsArray($a)
	{
		if (!$a) return false;
		$ret = [];
		for ($x = 0; $x < count($a); $x++) {
			$ret[$a[$x]['type']][$a[$x]['short_name']]['name'] = $a[$x]['name'];
			$ret[$a[$x]['type']][$a[$x]['short_name']]['value'] = $a[$x]['type'] === 'txt' ? str_replace(array('!BR!', '«', '»'), array("\r\n", '"', '"'), $a[$x]['value']) : $a[$x]['value'];
		}
		return $ret;
	}
}
