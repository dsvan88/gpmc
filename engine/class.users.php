<?
class Users {

    public function login($login,$password)
	{
		$usersArray = $this->getAssocArray($this->prepQuery('SELECT * FROM '.SQL_TBLUSERS.' WHERE name= ? OR username = ? OR email = ? ', array_fill(0,3,$login)));
		$userArrayCount = count($usersArray);
		if ($userArrayCount === 0) return false;

		for ($i=0; $i < $userArrayCount; $i++) {
			if (!password_verify($password, $usersArray[$i]['password'])) continue;
			unset($usersArray[$i]['password']);
            $usersArray[$i]['expire'] = $_SERVER['REQUEST_TIME']+CFG_MAX_SESSION_AGE+(mt_rand(0,3600)-1800);
            setcookie('_token', sha1(sha1($usersArray[$i]['expire'].$usersArray[$i]['login'])));
            $_SESSION = $usersArray[$i];
			return true;
		}
		return false;
	}
	public function adminLogIn($login,$password)
	{
		$usersArray = $this->getAssocArray($this->prepQuery('SELECT * FROM '.SQL_TBLUSERS.' WHERE ( name= ? OR username = ? OR email = ? ) AND ar > 0', array_fill(0,3,$login)));
		$userArrayCount = count($usersArray);
		if ($userArrayCount === 0) return false;

		for ($i=0; $i < $userArrayCount; $i++) {
			if (!password_verify($password, $usersArray[$i]['password'])) continue;
			unset($usersArray[$i]['password']);
            $_SESSION = $usersArray[$i];
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
    
	public function checkFreeLogin($string)
	{
		if ($this->getColumn($this->prepQuery('SELECT id FROM '.SQL_TBLUSERS.' WHERE username = ? LIMIT 1', [$string])) > 0)
			return false;
		return true;
	}

    function getUsersArray()
	{
		if ($r = $this->query('SELECT id,fio FROM '.SQL_TBLUSERS))
			return $this->getSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
}