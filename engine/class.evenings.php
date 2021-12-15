<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';

class Evenings {
    private $action;
    function __construct(){
        $this->action = $GLOBALS['CommonActionObject'];
    }
    // Получить ID вечера по дате
	function eveningGetId($d)
	{
		$dates = [$d-DATE_MARGE, $d+DATE_MARGE];
		return $this->action->getColumn($this->action->prepQuery('SELECT id FROM '.SQL_TBLEVEN.' WHERE date BETWEEN ? AND ? LIMIT 1', $dates));
	}
	// Получить дату вечера по его ID
	function eveningGetDate($id)
	{
		return $this->action->getColumn($this->action->prepQuery('SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(date)),"%d.%m.%Y %H:%i") FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1', [$id]));
	}
	// Простая проверка наличия ID вечера в базе
	function eveningCheckId($id)
	{
        return $this->action->recordExists(['id'=>$id],SQL_TBLEVEN);
	}
	// Получить информацию про последний состоявшийся вечер игр
	function lastEveningGetInfo()
	{
		return $this->action->getAssoc($this->action->query('SELECT id,DATE_FORMAT(DATE(FROM_UNIXTIME(date)),"%d.%m.%Y %H:%i") AS date,games,gamers,gamers_info FROM '.SQL_TBLEVEN.' ORDER BY id DESC LIMIT 1'));
	}
	// Утверждение планируемого вечера
	function setEveningApproved($data)
	{
		
		if ($data['place']['name'] !== ''){
			require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.places.php';
			$places = new Places;
			$placeData = $places->placeUpdateData($data['place']);
		}
		else $placeData['id'] = 0;

		$a = ['date'=>$data['date'],'place'=>$placeData['id'],'status'=>'new','game'=>$data['game']];

		if (isset($data['participants']))
		{
			require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
			$users = new Users;
			$data['participants'] = $users->participantsGetIds($data['participants']);
			if ($data['participants'] !== false){
				$a['participants'] = $data['participants']['ids'];
				unset($data['participants']['ids']);
				$a['participants_info'] = json_encode($data['participants'],JSON_UNESCAPED_UNICODE);
			}
		}
		else{
			$a['participants'] = '';
			$a['participants_info'] = '{}';
		}

		$eveningId = $data['eid'];

		unset($data['eid']);
		
		if ($eveningId == 0)
			$this->action->rowInsert($a,SQL_TBLEVEN);
		else 
			$this->action->rowUpdate($a,[ 'id'=>$eveningId ],SQL_TBLEVEN);
	}
	// Получение информации об ближайшем вечере игры
	function nearEveningGetData($columns = 'id')
	{
		if (is_array($columns))
		    $columns = implode(',',$columns);
		
        $data = $this->action->getAssoc($this->action->prepQuery("SELECT $columns FROM ".SQL_TBLEVEN.' WHERE date >= ? ORDER BY id DESC LIMIT 1', [$_SERVER['REQUEST_TIME']-DATE_MARGE]));

		if (!$data)
		{
			$data['ready'] = false;
			$data['start'] = false;
		}
		else 
			$data['start'] = $data['date']-$_SERVER['REQUEST_TIME'] < TIME_MARGE ? true : false;
		
		return $data;
	}
	// Получение информации об запланированных вечерах игры
	function eveningsGetBooked($type='')
	{
		$where = 'WHERE date >= ?';
		$values = [$_SERVER['REQUEST_TIME']-DATE_MARGE];
		if ($type != ''){
			$where .= 'AND game = ?';
			$values[] = $type;
		}

		return $this->action->getAssocArray($this->action->prepQuery('SELECT * FROM '.SQL_TBLEVEN." $where ORDER BY date", $values));
	}
	// Получение информации об вечерах игры по заданым критериям:
	// $from - метка времени с какой даты
	// $to - метка времени по какую дату
	function allEveningsGetData($from=0,$to=0) 
	{
		if ($from !== 0) $dop = ' AND date >= '.$from;
		if ($to !== 0) $dop = (isset($dop) ? $dop : '').' AND date <= '.$to;
		if ($r = $this->action->query('SELECT games,players FROM '.SQL_TBLEVEN.' WHERE id > 0 '.(isset($dop) ? $dop : '')))
			return $this->action->getAssocArray($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	// Получение информаций по всем играм за конкретный вечер
	function allGamesOfEvening($e_id)
	{
		return $this->GetAllGames($this->eveningGetGames($e_id));
	}
	// Получение списка игр за конкретный вечер
	function eveningGetGames($id)
	{
		return $this->action->getColumn($this->action->prepQuery('SELECT games FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1', [$id]));
	}
	// Получение информации об вечере игры по заданым критериям:
	// $columns - обычный массив из полей, которые нас интересуют
	// $by - ассоциативный массив, где ключ - имя поля, которое нас интересует, знаечение - значение этого поля
	function eveningGetData($columns = '*', $by = '')
	{
        $table = SQL_TBLEVEN;
        $conditions = '';
        $values = [];

        if ($columns !== '*'){
            if (is_array($columns))
                $columns = implode(',',$columns);
        }
        
        foreach($by as $key=>$value){
            $conditions .= " $key = ? ";
            $values[] = $value;
        }

		return $this->action->getAssoc($this->action->prepQuery("SELECT $columns FROM $table WHERE $conditions LIMIT 1",[$values]));
	}

	// Получение списка игроков, учасвстующих в $eid вечере
	function eveningGetPlayers($eid)
	{
		return $this->action->getColumn($this->action->prepQuery('SELECT participants FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1', [$eid]));
	}
	// Получение простого списка игроков, принимающих участие в $e вечере
	function playersGetAll($eid=-1) 
	{
		if ($eid !== -1) $dop = 'WHERE id IN ('.$this->eveningGetPlayers($eid).')';
		if ($r = $this->query('SELECT id,name FROM '.SQL_TBLUSERS.' '.(isset($dop) ? $dop : '')))
			return $this->getSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}

	function playerRemoveFromEvening($eveningId,$id)
	{
		$row = $this->action->getAssoc($this->action->prepQuery('SELECT participants,participants_info FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1',[$eveningId]));
		[$participants, $participantsInfo] = [explode(',',$row['participants']), json_decode($row['participants_info'],true)];

		$index = array_search($id,$participants);
		unset($participants[$index]);
		unset($participantsInfo[$index]);

		$array = [
			'participants' => implode(',',array_values($participants)),
			'participants_info' => json_encode(array_values($participantsInfo), JSON_UNESCAPED_UNICODE)
		];
		$this->action->rowUpdate($array,['id'=>$eveningId],SQL_TBLEVEN);
	}
	function playerAddToEvening($eveningId,$userData)
	{
		$row = $this->action->getAssoc($this->action->prepQuery('SELECT participants,participants_info FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1',[$eveningId]));
		[$participants, $participantsInfo] = [explode(',',$row['participants']), json_decode($row['participants_info'],true)];
		
		$index = array_search($userData['id'],$participants);
		if ($index !== false){
			if ($participantsInfo[$index]['arrive'] === $userData['arrive'])
				return false;
			$participantsInfo[$index]['arrive'] = $userData['arrive'];
		}
		else{
			if ($participants[0] === '')
				$participants = [$userData['id']];
			else 
				$participants[] = $userData['id'];
			$participantsInfo[] = [
				'name' => $userData['name'],
				'arrive' => $userData['arrive'],
				'duration' => $userData['duration'],
				'id' => $userData['id']
			];
		}
		$array = [
			'participants' => implode(',',array_values($participants)),
			'participants_info' => json_encode(array_values($participantsInfo), JSON_UNESCAPED_UNICODE)
		];
		$this->action->rowUpdate($array,['id'=>$eveningId],SQL_TBLEVEN);
		return true;
	}
	public function eveningsParticipantBookedByTelegram($game,$userData){

		$eveningData = $this->eveningsGetBooked($game);

		if (!$eveningData)
			return "Вечер игры в $game, пока - не запланирован!\r\nДождитесь начала регистрации!";
        if (!$this->playerAddToEvening($eveningData[0]['id'],$userData))
			return "Игрок $userData[name] уже зарегистрирован на ближайший вечер игры в $game! Планирует быть на $userData[arrive]";
        
		return "Игрок $userData[name] успешно зарегистрирован на ближайший вечер игры в $game! Планирует быть на $userData[arrive]";
	}
	public function eveningsParticipantUnbookedByTelegram($game,$id){

		$eveningData = $this->eveningsGetBooked($game);

		if (!$eveningData)
			return "Вечер игры в $game, пока - не запланирован!\r\nДождитесь начала регистрации!";
        if (!$this->playerRemoveFromEvening($eveningData[0]['id'],$id))
			return "Игрок $userData[name] отписался с ближайшего вечера игры в $game :(";
	}
}