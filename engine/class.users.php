<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.action.php';

class Users
{
	private $action;
	function __construct()
	{
		$this->action = $GLOBALS['CommonActionObject'];
	}
	public function login($data)
	{
		$login = strtolower(trim($data['login']));
		$password = sha1(trim($data['password']));

		$authData = $this->action->getAssoc($this->action->prepQuery(str_replace('{SQL_TBLUSERS}', SQL_TBLUSERS, 'SELECT * FROM {SQL_TBLUSERS} WHERE name ILIKE ? OR login = ? OR email = ? LIMIT 1'), array_fill(0, 3, $login)));
		if (password_verify($password, $authData['password'])) {
			unset($authData['password']);
			$_SESSION = $authData;
			if ($_SESSION['status'] === '')
				$_SESSION['status'] = 'user';
			$this->prolongSession();
			return true;
		}
		return false;
	}
	public function logout()
	{
		$_SESSION = [];
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				$_SERVER['REQUEST_TIME'] - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
			setcookie(
				'_token',
				'',
				$_SERVER['REQUEST_TIME'] - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}
		session_destroy();
		return true;
	}
	public function checkToken()
	{
		if (!isset($_SESSION['expire'])) {
			$this->logout();
			return false;
		}
		if (isset($_COOKIE['_token']) && $_COOKIE['_token'] === sha1(sha1($_SESSION['expire'] . $_SESSION['login']))) {
			if ($_SESSION['expire'] - $_SERVER['REQUEST_TIME'] < CFG_MAX_SESSION_AGE / 3) {
				$this->prolongSession();
			}
			return true;
		}
		return false;
	}
	public function prolongSession()
	{
		$_SESSION['expire'] = $_SERVER['REQUEST_TIME'] + CFG_MAX_SESSION_AGE + (mt_rand(0, 3600) - 1800);
		setcookie('_token', sha1(sha1($_SESSION['expire'] . $_SESSION['login'])));
		return true;
	}
	public function userRegistration($data)
	{
		if ($this->action->recordExists(['login' => $data['login']], SQL_TBLUSERS))
			return [
				'error' => 1,
				'text' => 'Користувач з таким логіном - вже існує! Оберіть, будь-ласка, інший',
				'wrong' => 'login'
			];

		$uid = $this->userGetId($data['name'], SQL_TBLUSERS);
		if ($uid <= 1)
			return [
				'error' => 1,
				'text' => 'Гравця з таким іменем, у базі не існує! Будь-ласка, відвідайте хоча б одну гру у нашому клубі',
				'wrong' => 'name'
			];

		$userData = [
			'login' => strtolower($data['login']),
			'password' => password_hash(sha1($data['password']), PASSWORD_DEFAULT),
			'email' => strtolower($data['email']),
			'telegram' => strtolower($data['telegram']),
		];
		$userData['id'] = $this->action->rowUpdate($userData, ['id' => $uid], SQL_TBLUSERS);

		if (!$userData['id']) return [
			'error' => 1,
			'text' => 'Користувач не був доданий. Перевірте дані або зверніться до адміністратора',
			'wrong' => 'login'
		];
		return true;
	}
	public function checkFreeLogin($string)
	{
		if ($this->action->getColumn($this->action->prepQuery('SELECT id FROM ' . SQL_TBLUSERS . ' WHERE login = ? LIMIT 1', [$string])) > 0)
			return false;
		return true;
	}
	function getUsersArray()
	{
		if ($r = $this->action->query('SELECT id,fio FROM ' . SQL_TBLUSERS))
			return $this->action->getSimpleArray($r);
		else error_log(__METHOD__ . ': SQL ERROR');
	}
	function usersGetNameAutoComplete($name)
	{
		if ($result = $this->action->prepQuery('SELECT name FROM ' . SQL_TBLUSERS . ' WHERE name ILIKE ? ', ["%$name%"]))
			return $this->action->getRawArray($result);
		else error_log(__METHOD__ . ': SQL ERROR');
	}
	function participantsGetIds($participants)
	{
		$data = [
			'enum' => []
		];
		foreach ($participants as $key => $value) {

			$name = trim($participants[$key]['name']);
			if ($name === '') {
				error_log(json_encode($participants[$key]));
				unset($participants[$key]);
				continue;
			}
			$participants[$key]['name'] = $name !== '+1' ? $name : 'tmp_user_' . $key;
		}

		if (count($participants) === 0)
			return false;

		$participants = array_values($participants);
		for ($x = 0; $x < count($participants); $x++) {
			$data[$x] = $participants[$x];
			$data[$x]['id'] = -1;
			$data['enum'][] = $participants[$x]['name'];
		}
		return $this->usersGetIds($data);
	}
	function usersGetIds($participants)
	{
		$keys = mb_substr(str_repeat('?,', count($participants['enum'])), 0, -1);
		$res = $this->action->prepQuery('SELECT id,name FROM ' . SQL_TBLUSERS . " WHERE name ILIKE ANY (ARRAY[$keys]) LIMIT 25",  $participants['enum']);
		unset($participants['enum']);
		while ($row = $this->action->getAssoc($res)) {
			$i = -1;
			while (isset($participants[++$i])) {
				if ($participants[$i]['name'] === $row['name']) {
					$participants[$i]['id'] = (int) $row['id'];
					break;
				}
			}
		}
		$i = -1;
		$participants['ids'] = '';
		while (isset($participants[++$i])) {
			if (trim($participants[$i]['name']) === '') continue;
			if ($participants[$i]['id'] === -1)
				$participants[$i]['id'] = $this->userAdd($participants[$i]['name']);
			$participants['ids'] .= 	$participants[$i]['id'] . ',';
		}
		$participants['ids'] = substr($participants['ids'], 0, -1);
		return $participants;
	}
	// Получение имени игрока по его ID в системе
	function userGetName($id)
	{
		if ($result = $this->action->getColumn($this->action->prepQuery('SELECT name FROM ' . SQL_TBLUSERS . ' WHERE id = ? LIMIT 1', [$id])))
			return $result;
		else return '';
	}
	// Получение ID в системе по никнейму в игре
	function userGetId($name)
	{
		if ($result = $this->action->getColumn($this->action->prepQuery('SELECT id FROM ' . SQL_TBLUSERS . ' WHERE name ILIKE ? LIMIT 1', [$name])))
			return $result;
		else return 0;
	}
	// Получение списка из $c случайних игроков, принимающих участие в $e вечере
	function usersGetRandomNames($count = 11, $eveninId = -1)
	{
		if ($eveninId !== -1) $usersIds = $this->action->getColumn($this->action->prepQuery('SELECT participants FROM ' . SQL_TBLEVEN . ' WHERE id = ? LIMIT 1', [$eveninId]));
		if ($result = $this->action->query('SELECT name FROM ' . SQL_TBLUSERS . (isset($usersIds) ? ' WHERE id IN (' . $usersIds . ')' : ' ORDER BY RANDOM() LIMIT ' . $count)))
			return $this->action->getRawArray($result);
		else error_log(__METHOD__ . ': SQL ERROR');
	}
	// Получить ассоциативный массив всех игроков, по заданным условиям.
	function usersGetData($columns = ['*'], $conditions = '', $limit = 1)
	{
		$method = ($limit !== 1) ? 'getAssocArray' : 'getAssoc';
		$where = '';
		$columns = implode(',', $columns);
		$table = SQL_TBLUSERS;
		$values = [];
		if ($conditions !== '' && is_array($conditions)) {
			$where = ' WHERE ';
			foreach ($conditions as $key => $value) {
				if (!is_array($value)) {
					$where .= "$key = ? OR ";
					$values[] = $value;
				} else {
					$where .= $key . ' IN (' . substr(str_repeat('?,', count($value)), 0, -1) . ') OR ';
					$values[] = array_merge($values, $value);
				}
			}
			$where = substr($where, 0, -4);
		}
		return $this->action->$method($this->action->prepQuery("SELECT $columns FROM $table $where" . ($limit !== 0 ? ' LIMIT ' . $limit : ' ORDER BY id '), $values));
	}
	function userUpdateData($data, $where)
	{
		return $this->action->rowUpdate($data, $where, SQL_TBLUSERS);
	}
	function userAdd($name)
	{
		return $this->action->rowInsert(['name' => $name], SQL_TBLUSERS);
	}
	function userDelete($uid)
	{
		return $this->action->rowDelete($uid, SQL_TBLUSERS);
	}
	// Получить количество всех игроков в системе.
	function GetGamerCount()
	{
		return $this->action->getColumn($this->action->query('SELECT count(id) FROM ' . SQL_TBLUSERS));
	}
	public function usersSaveNameFromTelegram($data)
	{
		$this->action->rowInsert($data, SQL_TBLUSERS);
	}
	public function usersSaveUnknowTelegram($data)
	{
		$this->action->rowInsert(['name' => 'tmp_telegram_user', 'telegram' => $data['telegram'], 'telegramId' => $data['telegramId']], SQL_TBLUSERS);
	}
}
