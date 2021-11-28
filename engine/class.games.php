<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.game-logs.php';

class Games {
    private $action;
    function __construct(){
        $this->action = $GLOBALS['CommonActionObject'];
    }
    function playersSetDefaultRoles(&$a)
	{
		$arr=array('enum' => []);
		$i=-1;
		while (isset($a['player'][++$i]))
		{
			$arr[$i]['id']		=	-1;
			$arr[$i]['role']	=	(int) $a['role'][$i];
			$arr[$i]['fouls']	=	0;
			$arr[$i]['out']		=	0;
			$arr[$i]['puted']	=	[];
			$arr[$i]['name']	=	$a['player'][$i];
			$arr['enum'][]	=	$a['player'][$i];
		}
		return $arr;
	}
    function defaultVarsGet(){
		return [
			'timer' => 6000,
			'stage'  => 'firstNight',
			'prevStage'  =>  '',
			'daysCount'  =>  -1,
			'activeSpeaker'  =>  -1,
			'prevActiveSpeaker'  =>  -1,
			'kill'  =>  [[]],
			'lastWill'  =>  [],
			'daySpeakers'  =>  [],
			'debaters'  =>  -1,
			'canMakeBestMove' => false,
			'makeBestMove' => -1,
			'currentVote'  => [],
			'bestMove'  =>  [],
			'dopsPoints'  =>  [0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0],
			'winTeam'  =>  0,
			'caption' => 'Фаза ночи.<br>Минута договора игроков мафии.<br>Шериф может взглянуть на город.'
		];
	}
	function gameLoadData($gameId){
		return $this->action->getAssoc($this->action->prepQuery('SELECT eid,players,vars FROM '.SQL_TBLGAMES.' WHERE id = ? LIMIT 1', [$gameId]));	
	}
	function gameGetDefaultData($gameId){

		$gameData = $this->gameResume($gameId);
		$players = json_decode($gameData['players'],true);
		$playersNames = [];
		for ($x=0; $x < count($players); $x++) {
			$playersNames[] = $players[$x]['name'];
		}
		return [
			'gid' => $gameId,
			'gnum' => $this->gameGetNumberOfEvening($gameData['eid'],$gameId),
			'win' => $gameData['win'],
			'reasons' => ['','Убит','Осуждён','4 Фола','Дисквал.'],
			'roles' => ['red','mafia','don','','sherif'],
			'roles_text' => ['Мирный','Мафия','Дон','','Шериф'],
			'rating' => ['C','B','A'],
			'manager' => $gameData['manager'],
			'players' => $players,
			'playersNames' => $playersNames
		];
	}
    function gameBegin($eveningId,$ids,$players,$manager) 
	{
        $users = new Users;
        $evenings = new Evenings;

		$_SESSION['id_game'] = $this->action->rowInsert([
			'eid'=>$eveningId,
			'player_ids'=>$ids,
			'manager'=>$users->userGetId($manager),
			'players'=>json_encode($players,JSON_UNESCAPED_UNICODE),
			'vars'=>json_encode($this->defaultVarsGet(),JSON_UNESCAPED_UNICODE),
			'rating'=> 0
		]);
		$gamesIds = $evenings->eveningGetGames($eveningId);

		$gamesIds = $gamesIds == '' ? $_SESSION['id_game'] : $gamesIds.','.$_SESSION['id_game'];
		$this->action->rowUpdate(['games'=>$gamesIds], ['id'=>$eveningId], SQL_TBLEVEN);
		return substr_count($gamesIds,',')+1;
	}
	function gameResume($gameId) 
	{
        $query = 'SELECT 
			{SQL_TBLGAMES}.id,
			{SQL_TBLGAMES}.players, 
			{SQL_TBLGAMES}.vars, 
			{SQL_TBLGAMES}.start, 
			{SQL_TBLGAMES}.rating, 
			{SQL_TBLGAMES}.win, 
			{SQL_TBLGAMES}.player_ids, 
			{SQL_TBLGAMES}.eid, 
			{SQL_TBLUSERS}.name AS manager
			FROM {SQL_TBLGAMES}
			LEFT JOIN {SQL_TBLUSERS} ON {SQL_TBLUSERS}.id = {SQL_TBLGAMES}.manager
			WHERE {SQL_TBLGAMES}.id = ? 
			LIMIT 1';
		return $this->action->getAssoc($this->action->prepQuery(str_replace(['{SQL_TBLGAMES}', '{SQL_TBLUSERS}'],[SQL_TBLGAMES, SQL_TBLUSERS],$query), [$gameId]));
	}
	function gameGetNumberOfEvening($eveningId,$gameId)
	{
		$games = $this->action->getColumn($this->action->prepQuery('SELECT games FROM '.SQL_TBLEVEN.' WHERE id = ? LIMIT 1', [$eveningId]));
		if (!$games)
			return 0;
		$games = explode(',',$games);
		return array_search($gameId,$games)+1;
	}
}