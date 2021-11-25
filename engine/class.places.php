<?php
class Places {
    private $action;
    function __construct(){
        $this->action = $GLOBALS['CommonActionObject'];
    }
    public function placeUpdateData($place)
	{
        $row = $this->action->getAssoc($this->action->prepQuery('SELECT id,name,info FROM '.SQL_TBLPLACES.' WHERE name = ? LIMIT 1',[$place['name']]));
		if ($row)
		{
			if ($place['info'] !== '' && $place['info'] !== $row['info'])
				$this->action->rowUpdate(['info'=>$place['info']],['id'=>$row['id']],SQL_TBLPLACES);
			return $row;
		}
		else {
            $place['id'] = $this->action->rowInsert($place,SQL_TBLPLACES);
            return $place;
        }
	}
    // Получение информации об месте игры по его ID в системе
    public function placeGetDataByID($id)
	{
		return $this->action->getAssoc($this->action->prepQuery('SELECT id,name,info FROM '.SQL_TBLPLACES.' WHERE id = ? LIMIT 1', [$id]));
	}
	//
	function placeGetNameAutoComplete($name) 
	{
		if ($result = $this->action->prepQuery('SELECT name FROM '.SQL_TBLPLACES.' WHERE name ILIKE ? ', ["%$name%"]))
			return $this->action->getRawArray($result);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function placeGetInfo($name) 
	{
		if ($result = $this->action->prepQuery('SELECT info FROM '.SQL_TBLPLACES.' WHERE name = ? LIMIT 1', [$name]))
			return $this->action->getColumn($result);
		else error_log(__METHOD__.': SQL ERROR');
	}
}