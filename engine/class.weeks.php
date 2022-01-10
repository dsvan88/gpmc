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
		if ($result !== [])
			return $result[0];
		return false;
	}
	public function getDataDefault()
	{
		$time = time() - 604800;
		$result = $this->action->getSimpleArray($this->action->prepQuery('SELECT data FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]));
		if ($result !== []) {
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
		/* $data = [
			'weekId' => (int) $_POST['weekId'],
			'dayId' => (int) $_POST['dayId'],
			'game' => trim($_POST['game']),
			'time' => $_POST['day_time']
		];

		if (isset($_POST['participant'])) {
			$data['participants'] = [];
			for ($i = 0; $i < count($_POST['participant']); $i++) {
				$data['participants'][] = [
					'name' => $_POST['participant'][$i],
					'arrive' => $_POST['arrive'][$i],
					'duration' => $_POST['duration'][$i],
				];
			}
		} */
		$weekId = $data['weekId'];
		unset($data['weekId']);
		$dayId = $data['dayId'];
		unset($data['dayId']);

		$weekData = $this->getDataById($weekId);
		if ($weekData !== false) {
			$weekData['data'][$dayId] = $data;
			$result = $this->action->rowUpdate(['data' => json_encode($weekData)], ['id' => $weekId], SQL_TBLWEEKS);
			if ($result)
				return true;
			return false;
		} else {
			$weekData = [
				'data' => json_encode(
					[
						$dayId => $data
					],
					JSON_UNESCAPED_UNICODE
				)
			];
			$result = $this->action->rowInsert($weekData, SQL_TBLWEEKS);
			if ($result !== 0)
				return true;
			return false;
		}
	}
}
