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
			$time = $_SERVER['REQUEST_TIME'];
		$result = $this->action->getAssocArray($this->action->prepQuery('SELECT id,data,start,finish FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]));
		if ($result !== []) {
			$result = $result[0];
			$result['data'] = json_decode($result['data'], true);
			return $result;
		}
		return false;
	}
	// Получить настройки недели по id недели
	public function getDataById($id)
	{
		$result = $this->action->getAssocArray($this->action->prepQuery('SELECT id,data,start FROM ' . SQL_TBLWEEKS . ' WHERE id = ? LIMIT 1', [$id]));
		if ($result !== []) {
			$result = $result[0];
			$result['data'] = json_decode($result['data'], true);
			return $result;
		}
		return false;
	}
	// Получить настройки последней зарегистрированной недели
	public function getLastWeekData()
	{
		$result = $this->action->getAssocArray($this->action->query('SELECT id,data FROM ' . SQL_TBLWEEKS . ' ORDER BY id DESC LIMIT 1'));
		if ($result !== []) {
			$result = $result[0];
			$result['data'] = json_decode($result['data'], true);
			return $result;
		}
		return false;
	}
	public function getDataDefault($sunday = 0)
	{
		if ($sunday === 0) {
			$result = $this->getLastWeekData();
		} else {
			$time = $sunday - 604800;
			$result = $this->getDataByTime($time);
		}

		if ($result) {
			$weekData = $result;
			$weekData['id'] = 0;
			if (is_string($weekData['data'])) {
				$weekData['data'] = json_decode($weekData['data'], true);
			}
			for ($i = 0; $i < 7; $i++) {
				$weekData['data'][$i]['participants'] = [];
			}
		} else {
			$weekData = [
				'id' => 0,
				'data' => []
			];
			for ($i = 0; $i < 7; $i++) {
				$weekData['data'][] = $this->getDayDataDefault();
			}
		}
		return $weekData;
	}
	public function getDayDataDefault()
	{
		return [
			'game' => 'mafia',
			'mods' => [],
			'time' => '18:00',
			'participants' => []
		];
	}
	public function daySetApproved($data)
	{
		$weekId = $data['weekId'];
		unset($data['weekId']);
		$dayId = $data['dayId'];
		unset($data['dayId']);

		$weekData = false;

		if ($weekId > 0) {
			$weekData = $this->getDataById($weekId);
			if ($weekData !== false)
				$weekId = $weekData['id'];
		}

		if ($weekId > 0) {
			$weekData['data'][$dayId] = $data;
			$result = $this->action->rowUpdate(['data' => json_encode($weekData['data'])], ['id' => $weekId], SQL_TBLWEEKS);
			if ($result)
				return $weekId;
			return false;
		} else {
			$sunday = strtotime('next sunday 23:00:00');
			$monday = strtotime('last monday 12:00:00', $sunday);
			if ($weekId === -1) {
				$sunday += 604800;
				$monday += 604800;
			}
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
	public function dayUserRegistrationByTelegram($data)
	{
		$weekData = $this->getDataByTime();
		$id = -1;
		if (!isset($weekData['data'][$data['dayNum']])) {
			if (!in_array($data['userStatus'], ['admin', 'manager']))
				return 'Игр на указанный день, пока не запланировано!';
			else {
				$defaultData = $this->getDataDefault();
				$weekData['data'][$data['dayNum']] = $defaultData['data'][$data['dayNum']];
				if ($data['arrive'] !== '')
					$weekData['data'][$data['dayNum']]['time'] = $data['arrive'];
				$data['arrive'] = '';
			}
		} else {
			foreach ($weekData['data'][$data['dayNum']]['participants'] as $index => $userData) {
				if ($userData['id'] === $data['userId']) {
					$id = $index;
					break;
				}
			}
		}

		if ($id !== -1)
			return 'Вы уже зарегистрированны за этот день!';

		$newData = $weekData['data'][$data['dayNum']];
		$newData['weekId'] = $weekData['id'];
		$newData['dayId'] = $data['dayNum'];

		$freeSlot = -1;
		while (isset($newData['participants'][++$freeSlot])) {
		}

		$newData['participants'][$freeSlot] = [
			'id'	=>	$data['userId'],
			'name'	=>	$data['userName'],
			'arrive'	=>	$data['arrive'],
			'duration'	=> 	(int) $data['duration']
		];

		$result = $this->daySetApproved($newData);

		if (!$result) {
			return json_encode($newData, JSON_UNESCAPED_UNICODE);
		}

		$dayNames = ['в <b>Понедельник</b>', 'во <b>Вторник</b>', 'в <b>Среду</b>', 'в <b>Четверг</b>', 'в <b>Пятницу</b>', 'в <b>Субботу</b>', 'в <b>Воскресенье</b>'];
		$gameNames = [
			'mafia' => 'Мафия',
			'poker' => 'Покер',
			'board' => 'Настолки',
			'cash' => 'Кеш-покер'
		];
		return "Вы успешно зарегистрированны на игру <b>'{$gameNames[$weekData['data'][$data['dayNum']]['game']]}'</b> {$dayNames[$data['dayNum']]}.";
	}
	public function dayUserUnregistrationByTelegram($data)
	{
		$weekData = $this->getDataByTime();

		if (!isset($weekData['data'][$data['dayNum']])) {
			return 'Игр на указанный день, пока не запланировано!';
		}

		$id = -1;
		foreach ($weekData['data'][$data['dayNum']]['participants'] as $index => $userData) {
			if ($userData['id'] === $data['userId']) {
				$id = $index;
				unset($weekData['data'][$data['dayNum']]['participants'][$index]);
				break;
			}
		}
		if ($id === -1)
			return 'Вы не были записаны на этот день!';

		$newData = $weekData['data'][$data['dayNum']];
		$newData['weekId'] = $weekData['id'];
		$newData['dayId'] = $data['dayNum'];

		$result = $this->daySetApproved($newData);

		if (!$result) {
			return json_encode($newData, JSON_UNESCAPED_UNICODE);
		}
		$dayNames = ['в <b>Понедельник</b>', 'во <b>Вторник</b>', 'в <b>Среду</b>', 'в <b>Четверг</b>', 'в <b>Пятницу</b>', 'в <b>Субботу</b>', 'в <b>Воскресенье</b>'];
		$gameNames = [
			'mafia' => 'Мафия',
			'poker' => 'Покер',
			'board' => 'Настолки',
			'cash' => 'Кеш-покер'
		];
		return "Вы успешно отписались от игры <b>'{$gameNames[$weekData['data'][$data['dayNum']]['game']]}'</b> {$dayNames[$data['dayNum']]}.";
	}
	public function getCount()
	{
		return $this->action->getColumn($this->action->query('SELECT count(id) FROM ' . SQL_TBLWEEKS));
	}
	public function getIds()
	{
		return $this->action->getRawArray($this->action->query('SELECT id FROM ' . SQL_TBLWEEKS . ' ORDER BY id'));
	}
	public function checkByTime($time = 0)
	{
		if ($time === 0)
			$time = $_SERVER['REQUEST_TIME'];
		$result = $this->action->getColumn($this->action->prepQuery('SELECT id FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]));
		return $result > 0 ? true : false;
	}
	public function getCurrentId()
	{
		$time = $_SERVER['REQUEST_TIME'];
		$result = $this->action->getColumn($this->action->prepQuery('SELECT id FROM ' . SQL_TBLWEEKS . ' WHERE start < :time AND finish > :time LIMIT 1', ['time' => $time]));
		return $result;
	}
	public function autoloadWeekData($weekId)
	{
		$cId = $this->getCurrentId();
		$wIds = $this->getIds();
		$wIdsInList = -1;
		if ($cId)
			$wIdsInList = array_search($cId, $wIds);

		if ($weekId > 0) {
			return [$cId, $wIds, $wIdsInList, $this->getDataById($weekId)];
		}
		if ($weekId === 0) {
			if ($cId) {
				return [$cId, $wIds, $wIdsInList, $this->getDataById($cId)];
			}
			return [$cId, $wIds, $wIdsInList, $this->getDataDefault()];
		}
		if ($weekId === -1) {
			/* 			if ($cId) {
				$sunday = strtotime('next sunday') + 604800;
				return [$cId, $wIds, $wIdsInList, $this->getDataDefault($sunday)];
			} */
			return [$cId, $wIds, $wIdsInList, $this->getDataDefault()];
		}
	}
}
