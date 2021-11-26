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
			'daySpeakers'  =>  -1,
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
    function gameBegin($eveningId,$ids,$players,$manager) 
	{
        $users = new Users;
        $evenings = new Evenings;

		$_SESSION['id_game'] = $this->action->rowInsert([
			'eid'=>$eveningId,
			'player_ids'=>$ids,
			'manager'=>$users->userGetId($manager),
			'players'=>json_encode($players,JSON_UNESCAPED_UNICODE),
			'rating'=> 0,
			'vars'=>json_encode($this->defaultVarsGet(),JSON_UNESCAPED_UNICODE)
		]);
		$gamesIds = $evenings->eveningGetGames($eveningId);

		$gamesIds = $gamesIds == '' ? $_SESSION['id_game'] : $gamesIds.','.$_SESSION['id_game'];
		$this->action->rowUpdate(['games'=>$games], ['id'=>$eveningId], SQL_TBLEVEN);
		return substr_count($gamesIds,',')+1;
	}
	function gameResume($gameId) 
	{
        $where = '';
        $values = [];
        if ($gameId > 0){
            $where = ' WHERE id= ? ';
            $values[] = $gameId;
        }
		return $this->action->getAssoc($this->action->prepQuery('SELECT id,players,vars,manager,rating,win,player_ids,eid,start FROM '.SQL_TBLGAMES.$where.' ORDER BY id DESC LIMIT 1', $values));
	}
	function gameGetNumberOfEvening($e,$g)
	{
		if (($r = $this->getRawArray($this->query('SELECT `games` FROM `'.SQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))) !== false)
			$r = substr_count($r[0],',') + 1;
		return $r;
	}
}