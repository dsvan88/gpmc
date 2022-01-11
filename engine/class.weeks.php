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
	public function getDataByTime($time = 0)
	{
		if ($time === 0)
			$time = time();
		$result = $this->action->getSimpleArray($this->action->prepQuery('SELECT id,data FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]));
		if ($result !== [])
			return $result;
		return false;
	}
	// Получить настройки недели по времени
	public function getDataById($id)
	{
		$result = $this->action->getAssocArray($this->action->prepQuery('SELECT id,data FROM ' . SQL_TBLWEEKS . ' WHERE id = ? LIMIT 1', [$id]));
		if ($result !== []) {
			$result = $result[0];
			$result['data'] = json_decode($result['data'], true);
			return $result;
		}
		return false;
	}
	public function getDataDefault()
	{
		$time = time() - 604800;
		$result = $this->getDataByTime($time);
		if ($result) {
			$weekData = $result;
			$weekData['data'] = json_decode($weekData['data'], true);
		} else {
			$weekData = [
				'id' => 0,
				'data' => []
			];
			for ($i = 0; $i < 7; $i++) {
				$weekData['data'][] = [
					'game' => 'mafia',
					'mods' => '',
					'time' => '18:00',
					'participants' => []
				];
			}
		}
		return $weekData;
	}
	public function daySetApproved($data)
	{
		$weekId = $data['weekId'];
		unset($data['weekId']);
		$dayId = $data['dayId'];
		unset($data['dayId']);

		if ($weekId === 0)
			$weekData = $this->getDataById($weekId);

		if ($weekData !== false && $weekId !== 0) {
			$weekData['data'][$dayId] = $data;
			$result = $this->action->rowUpdate(['data' => json_encode($weekData)], ['id' => $weekId], SQL_TBLWEEKS);
			if ($result)
				return $weekId;
			return false;
		} else {
			$sunday = strtotime('next sunday');
			$monday = strtotime('last monday', $sunday);
			$weekData = [
				'start' => $monday,
				'finish' => $sunday,
				'data' => json_encode([$dayId => $data], JSON_UNESCAPED_UNICODE)
			];

			$result = $this->action->rowInsert($weekData, SQL_TBLWEEKS);
			if ($result !== 0)
				return $result;
			return false;
		}
	}
}
