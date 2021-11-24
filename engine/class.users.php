<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';

class Users {
	private $action;
    function __construct(){
        $this->action = $GLOBALS['CommonActionObject'];
    }
	public function login($data){
        $data = [
            'login' => strtolower(trim($data['login'])),
            'password' => sha1(trim($data['password']))
        ];
        $authData = $this->action->getAssoc($this->action->prepQuery(str_replace('{SQL_TBLUSERS}', SQL_TBLUSERS, 'SELECT * FROM {SQL_TBLUSERS} WHERE name = ? OR login = ? OR email = ? LIMIT 1'),array_fill(0,3,$data['login'])));
        if (password_verify($data['password'], $authData['password'])){
            unset($authData['password']);
            $_SESSION = $authData;
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
            setcookie(session_name(), '', $_SERVER['REQUEST_TIME'] - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
            setcookie('_token', '', $_SERVER['REQUEST_TIME'] - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        return true;
	}
	public function checkToken(){

        if (isset($_COOKIE['_token']) && $_COOKIE['_token'] === sha1(sha1($_SESSION['expire'].$_SESSION['login']))){
            if ($_SESSION['expire'] - $_SERVER['REQUEST_TIME'] < CFG_MAX_SESSION_AGE/3){
                $this->prolongSession();
            }
            return true;
        }
        return false;
    }
	public function prolongSession(){

        $_SESSION['expire'] = $_SERVER['REQUEST_TIME']+CFG_MAX_SESSION_AGE+(mt_rand(0,3600)-1800);
        setcookie('_token', sha1(sha1($_SESSION['expire'].$_SESSION['login'])));
        return true;
    }
    // public function getCryptKey(){
    //     return $this->getColumn($this->query(str_replace('{TABLE_AUTH}', TABLE_AUTH, 'SELECT key FROM {TABLE_AUTH} WHERE id = 1 LIMIT 1')));
    // }
	public function checkFreeLogin($string)
	{
		if ($this->action->getColumn($this->action->prepQuery('SELECT id FROM '.SQL_TBLUSERS.' WHERE login = ? LIMIT 1', [$string])) > 0)
			return false;
		return true;
	}
    function getUsersArray()
	{
		if ($r = $this->action->query('SELECT id,fio FROM '.SQL_TBLUSERS))
			return $this->getSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
    function participantsGetIds($participants){
		$data = [
			'enum' => []
		];
		for ($x=0;$x<count($participants);$x++){
			$name = trim($participants[$x]['name']);
			if ($name === '')
			{
				unset($participants[$x]);
				continue;
			}
			$participants[$x]['name'] = $name !== '+1' ? $name : 'tmp_user_'.$x;
		}
		$participants = array_values($participants);
		for ($x=0;$x<count($participants);$x++){
			$data[$x] = $participants[$x];
			$data[$x]['id'] = -1;
			$data['enum'][] = $participants[$x]['name'];
		}
		return $this->usersGetIds($data);
	}
    function usersGetIds($participants)
	{
        $keys = mb_substr(str_repeat('?,',count($participants['enum'])),0,-1);
		$res = $this->action->prepQuery('SELECT id,name FROM '.SQL_TBLUSERS." WHERE name IN ($keys) LIMIT 25",  $participants['enum']);
		unset($participants['enum']);
		while ($row = $this->action->getAssoc($res))
		{
			$i = -1;
			while(isset($participants[++$i])){
				if ($participants[$i]['name'] === $row['name'])
				{
					$participants[$i]['id'] = (int) $row['id'];
					break;
				}
			}
		}
		$i = -1;
		$participants['ids'] = '';
		while(isset($participants[++$i]))
		{
			if (trim($participants[$i]['name']) === '') continue;
			if ($participants[$i]['id'] === -1)
				$participants[$i]['id'] = $this->action->rowInsert([ 'name'=>$participants[$i]['name'] ],SQL_TBLUSERS);
			$participants['ids'] .= 	$participants[$i]['id'].',';
		}
		$participants['ids'] = substr($participants['ids'],0,-1);
		return $participants;
	}
}