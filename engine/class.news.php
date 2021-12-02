<?php
/* require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.game-logs.php'; */

class News {
    private $action;
    function __construct(){
        $this->action = $GLOBALS['CommonActionObject'];
    }
    function newsCreate(&$a)
	{
		return $this->action->rowInsert($a, SQL_TBLNEWS);
	}
	public function newsGetPerPage($page = 0){
        $searchQuery = 'SELECT * FROM '.SQL_TBLNEWS.' WHERE type = ? ';
		$values = ['news'];
		$searchQuery .= ' ORDER BY id DESC';

        if ($page === 0)
            $searchQuery .= ' LIMIT '.CFG_NEWS_PER_PAGE;
        else
            $searchQuery .= ' LIMIT '.CFG_NEWS_PER_PAGE.' OFFSET '.(CFG_NEWS_PER_PAGE*$page);
        
        return $this->action->getAssocArray($this->action->prepQuery($searchQuery, $values));
    }
    function newsGetCount()
	{
		return $this->action->getColumn($this->action->prepQuery('SELECT COUNT(id) FROM '.SQL_TBLNEWS.' WHERE type = ? ', ['news']));
	}
    function newsGetAll()
	{
		return $this->action->getAssocArray($this->action->query('SELECT * FROM '.SQL_TBLNEWS));
	}
    function newsGetData($where)
	{
		return $this->action->getAssoc($this->action->prepQuery('SELECT * FROM '.SQL_TBLNEWS.' WHERE id = :id', $where));
	}
}