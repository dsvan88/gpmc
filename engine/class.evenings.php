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

		$a = ['date'=>$data['date'],'place'=>$placeData['id'],'status'=>'new'];

		if (isset($data['participants']))
		{
			require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
			$users = new Users;
			$data['participants'] = $users->participantsGetIds($data['participants']);
			$a['participants'] = $data['participants']['ids'];
			unset($data['participants']['ids']);
			$a['participants_info'] = json_encode($data['participants'],JSON_UNESCAPED_UNICODE);
		}
		else{
			$a['participants'] = '';
			$a['participants_info'] = '{}';
		}

		$evening = $this->nearEveningGetData(['id','date']);
		if (!isset($evening['id']))
			$this->action->rowInsert($a,SQL_TBLEVEN);
		else 
			$this->action->rowUpdate($a,[ 'id'=>$evening['id'] ],SQL_TBLEVEN);
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

	function playerRemoveFromEvening($e,$id)
	{
		$row = $this->action->getAssoc($this->action->prepQuery('SELECT participants,participants_info FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1',[$e]));
		[$participants, $participantsInfo] = [explode(',',$row['participants']), json_decode($row['participants_info'],true)];
		
		unset($participants[$id]);
		unset($participantsInfo[$id]);

		/* for ($x=0; $x < count($participants); $x++) { 
			if ($participants[$x] == $id){
				unset($participants[$x]);
				unset($participantsInfo[$x]);
				break;
			}
		} */

		$array = [
			'participants' => implode(',',array_values($participants)),
			'participants_info' => json_encode(array_values($participantsInfo), JSON_UNESCAPED_UNICODE)
		];
		$this->action->rowUpdate($array,['id'=>$e],SQL_TBLEVEN);
	}
}