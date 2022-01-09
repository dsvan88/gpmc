<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.action.php';

class Weeks
{
	private $action;
	function __construct()
	{
		$this->action = $GLOBALS['CommonActionObject'];
	}
	// Получить настройки недели по времени
	function getData($time = 0)
	{
		if ($time === 0)
			$time = time();
		$result = $this->action->prepQuery('SELECT id,data FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]);
		if ($result)
			return $this->action->getSimpleArray($result);
		return false;
	}
}
