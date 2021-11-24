<?php
if (!defined('JSFUNC_LOAD'))
	define('JSFUNC_LOAD',true);
class JSFunc extends SQLBase
{
	protected $SQL;
	function GetNamesAutoComplete($s,$e=0) 
	{
		$dop = '';
		if ($e > 0) $dop = ' AND `id` IN ('.$this->eveningGetPlayers($e).')';
		if ($r = $this->query('SELECT `name` FROM `'.SQL_TBLUSERS.'` WHERE `name` LIKE "%'.$s.'%"'.$dop))
			return $this->getSimpleString($r,'","');
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetPlacesAutoComplete($s,$z='') 
	{
		if ($r = $this->query('SELECT `pl_name` FROM `'.SQL_TBLPLACES.'` WHERE `pl_name` LIKE "%'.$s.'%"'))
			return $this->getSimpleString($r,'","');
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetPlacesInfo($s) 
	{
		if ($r = $this->query('SELECT `pl_info` FROM `'.SQL_TBLPLACES.'` WHERE `pl_name` = "'.$s.'" LIMIT 1'))
			return $this->getRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetResidentsNames($c=11)
	{
		if ($r = $this->query('SELECT `id`,`name` FROM `'.SQL_TBLUSERS.'` WHERE `status` > 0 ORDER BY `id` LIMIT '.$c))
			return $this->getAssocArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetRandomPlayers($c=10,$e=-1) 
	{
		// Переделать, опираясь на новую колонку "gamers_info"
		if ($e !== -1) $dop = $this->getRawArray($this->query('SELECT `gamers` FROM `'.SQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
		if ($r = $this->query('SELECT `name` FROM `'.SQL_TBLUSERS.'` '.(isset($dop) ? 'WHERE `id` IN ('.$dop.')' : 'ORDER BY RAND() LIMIT '.$c)))
			return $this->getRawArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function allGamesOfEvening($e_id)
	{
		return $this->GetAllGames($this->eveningGetGames($e_id));
	}
	function GetAllGamesByDate($d)
	{
		return $this->GetAllGames($this->eveningGetGames($this->eveningGetId($d)));
	}
	function GetAllGames($ids=0) 
	{
		if ($r = $this->query('SELECT `id`,`players`,`vars`,`win`,`manager`,`rating`,`g_ids` FROM `'.SQL_TBLGAMES.'` WHERE `win` > 0'.($ids !== 0 ? ' AND `id` IN ('.$ids.')' : '')))
			return $this->getAssocArray($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	function GetAllGameIDs($f_date=0,$t_date=0) 
	{
		if ($f_date !== 0) $dop = ' AND `date` >= '.$f_date;
		if ($t_date !== 0) $dop = (isset($dop) ? $dop : '').' AND `date` <= '.$t_date;
		if ($r = $this->query('SELECT `games` FROM `'.SQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->getSimpleString($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	function playersGetAllIDs($f_date=0,$t_date=0) 
	{
		if ($f_date !== 0) $dop = ' AND `date` >= '.$f_date;
		if ($t_date !== 0) $dop = (isset($dop) ? $dop : '').' AND `date` <= '.$t_date;
		if ($r = $this->query('SELECT `players` FROM `'.SQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->getSimpleString($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	// Получение информации об вечерах игры по заданым критериям:
	// $f - метка времени с какой даты
	// $t - метка времени по какую дату
	function allEveningsGetData($f=0,$t=0) 
	{
		if ($f !== 0) $dop = ' AND `date` >= '.$f;
		if ($t !== 0) $dop = (isset($dop) ? $dop : '').' AND `date` <= '.$t;
		if ($r = $this->query('SELECT `games`,`gamers` FROM `'.SQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->getAssocArray($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	function playersGetAll($e=-1) 
	{
		if ($e !== -1) $dop = 'WHERE `id` IN ('.$this->eveningGetPlayers($e).')';
		if ($r = $this->query('SELECT `id`,`name` FROM `'.SQL_TBLUSERS.'` '.(isset($dop) ? $dop : '')))
			return $this->getSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function eveningGetPlayers($e)
	{
		return $this->getRawArray($this->query('SELECT `gamers` FROM `'.SQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
	}
	function eveningGetGames($e)
	{
		return $this->getRawArray($this->query('SELECT `games` FROM `'.SQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
	}
	function ParsePostGamers()
	{
		$a = array();
		$i=-1;
		// Отсекаем пустые значения
		while(isset($_POST['gamer'][++$i]))
		{
			$nn = trim($_POST['gamer'][$i]);
			if ($nn === '')
			{
				unset($_POST['gamer'][$i]);
				continue;
			}
			$_POST['gamer'][$i] = $nn;
		}
		// Берем только значения. (от предыдущей операции остаются пробелы, если пустые поля были в середине массива)
		$_POST['gamer'] = array_values($_POST['gamer']);
		$i=-1;
		while(isset($_POST['gamer'][++$i]))
		{
			$_POST['gamer'][$i] = $_POST['gamer'][$i] !== '+1' ? $_POST['gamer'][$i] : 'tmp_user_'.$i;
			$a[$i]['id'] = -1;
			$a[$i]['name'] = $_POST['gamer'][$i];
		}
		$a['enum'] = '"'.implode('","',$_POST['gamer']).'"';
		return $this->usersGetIds($a);
	}

	function eveningCheckId($i)
	{
		if (($r = $this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLEVEN.'` WHERE `id` ="'.$i.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function nearEveningGetData($c = 'id')
	{
		if (!is_array($c))
		{
			$r = $this->getRawArray($this->query('SELECT `'.$c.'` FROM `'.SQL_TBLEVEN.'` WHERE `date` >="'.($_SERVER['REQUEST_TIME']-DATE_MARGE).'" ORDER BY `id` DESC LIMIT 1'));
			if (isset($r[0]) && $r[0] != '') return $r[0];
		}
		else
		{
			$r = $this->getAssoc($this->query('SELECT `'.implode('`,`',$c).'` FROM `'.SQL_TBLEVEN.'` WHERE `date` >="'.($_SERVER['REQUEST_TIME']-DATE_MARGE).'" ORDER BY `id` DESC LIMIT 1'));
			if (isset($r) && count($r) > 0) return $r;
		}
		return false;
	}
	function eveningGetData($c,$b)
	{
		return $this->getAssoc($this->query('SELECT `'.implode('`,`',$c).'` FROM `'.SQL_TBLEVEN.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	function eveningGetId($d)
	{
		if (($r = $this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLEVEN.'` WHERE `date` BETWEEN '.$d.' AND '.($d+82800).' LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function eveningGetDate($id)
	{
		if (($r = $this->getRawArray($this->query('SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") FROM `'.SQL_TBLEVEN.'` WHERE `id` ="'.$id.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function lastEveningGetInfo()
	{
		if ($r = $this->getAssoc($this->query('SELECT `id`,DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") AS `date`,`games`,`gamers` FROM `'.SQL_TBLEVEN.'` ORDER BY `id` DESC LIMIT 1')))
			return $r;
		else return false;
	}
	function SetEveningID($d,$p)
	{
		if (($r = $this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLEVEN.'` WHERE `date` ="'.$d.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return $this->rowInsert(array('date'=>$d,'gamers'=>$p),SQL_TBLEVEN);
	}
	function GameExists($id)
	{
		if ($this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLGAMES.'` WHERE `id` = "'.$id.'" AND `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0] == $id)
			return true;
		else return false;
	}
	function TryResumeGame() 
	{
		return ($_SESSION['id_game'] = $this->getRow($this->query('SELECT `id` FROM `'.SQL_TBLGAMES.'` WHERE `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0]);
	}
	function ResumeGame($g_id) 
	{
		if ($r = $this->getAssoc($this->query('SELECT `id`,`players`,`vars`,`manager`,`rating`,`win`,`g_ids`,`e_id`,`start` FROM `'.SQL_TBLGAMES.'` WHERE '.($g_id > 0 ? '`id`="'.$g_id.'"' : '`win`<1').' ORDER BY `id` DESC LIMIT 1')))
			$r = str_replace(array('»','}\",\"{','[\"','\"]'),array('"','}","{','["','"]'),$r);
		return $r;
	}
	function GetGameNum($e,$g)
	{
		if (($r = $this->getRawArray($this->query('SELECT `games` FROM `'.SQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))) !== false)
			$r = substr_count($r[0],',') + 1;
		return $r;
	}
	function getDefaultVars(){
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
			'dopsPoints'  =>  [0=>0.0,1=>0.0,2=>0.0,3=>0.0,4=>0.0,5=>0.0,6=>0.0,7=>0.0,8=>0.0,9=>0.0],
			'winTeam'  =>  0,
			'caption' => 'Фаза ночи.<br>Минута договора игроков мафии.<br>Шериф может взглянуть на город.'
		];
	}
	function StartGame(&$e,&$ids,&$players,&$m) 
	{
		$_SESSION['id_game'] = $this->rowInsert([
			'e_id'=>$e,
			'g_ids'=>$ids,
			'manager'=>$this->GetGamerID($m),
			'players'=>json_encode($players,JSON_UNESCAPED_UNICODE),
			'rating'=>$this->GetGameRating($ids),
			'vars'=>json_encode($this->getDefaultVars(),JSON_UNESCAPED_UNICODE),
		]);
		$games = $this->eveningGetGames($e);
		$games = $games == '' ? $_SESSION['id_game'] : $games.','.$_SESSION['id_game'];
		$this->rowUpdate(array('games'=>$games), array('id'=>$e), SQL_TBLEVEN);
		return substr_count($games,',')+1;
	}
	function SetPlayersDefaults(&$a)
	{
		// error_log(json_encode($a,JSON_UNESCAPED_UNICODE));
		$arr=array('enum' => '');
		$i=-1;
		while (isset($a['player'][++$i]))
		{
			$arr[$i]['id']		=	-1;
			$arr[$i]['role']	=	(int) $a['role'][$i];
			$arr[$i]['fouls']	=	0;
			$arr[$i]['out']		=	0;
			$arr[$i]['puted']	=	array();
			$arr[$i]['name']	=	$a['player'][$i];
		}
		$arr['enum']		= 	'"'.implode('","',$a['player']).'"';
		return $arr;
	}
	function GetGamerID($n,$chk=0)
	{
		if (($r = $this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLUSERS.'` WHERE `name` ="'.$n.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return $chk === 0 ? $this->rowInsert(array('name'=>$n),SQL_TBLUSERS) : false;
	}
	function GetGamersNames($ids,$s = false)
	{
		if (count($r = $this->getAssocArray($this->query('SELECT `id`,`name` FROM `'.SQL_TBLUSERS.'` WHERE `id` IN ('.$ids.') ORDER BY `status` DESC'))) > 0)
		{
			if (!$s)
				return $r;
			else
			{
				$r2 = array();
				$ids = explode(',',$ids);
				for($x=0;$x<count($ids);$x++)
				{
					$r2[$x]['id'] = $ids[$x];
					for($y=0;$y<count($r);$y++)
					{
						if ($r2[$x]['id'] === $r[$y]['id'])
						{
							$r2[$x]['name'] = $r[$y]['name'];
							break;
						}
					}
				}
				return $r2;
			}
		}
		else return false;
	}
	function GetGameRating($ids)
	{
		if (($r = $this->getRawArray($this->query('SELECT `rank` FROM `'.SQL_TBLUSERS.'` WHERE `id` IN ('.$ids.') LIMIT 20'))) !== false)
		{
			$i=-1;
			$res = 0;
			while(++$i<count($r))
				$res += $r[$i];
			if ($res >= 15) $i = 2;
			else if ($res >= 8) $i = 1;
			else $i = 0;
			return $i;
		}
		else return 0;
	}
	function AddGamerToEvening($e,$id,$t='')
	{
		$ids = $this->eveningGetPlayers($e).','.$id;
		$this->rowUpdate(array('gamers'=>$ids),array('id'=>$e),SQL_TBLEVEN);
		return $ids;
	}
	function RemoveGamerFromEvening($e,$id)
	{
		$ids = str_replace(array($id.',',','.$id),array('',''),$this->eveningGetPlayers($e));
		$this->rowUpdate(array('gamers'=>$ids),array('id'=>$e),SQL_TBLEVEN);
	}
	function UnRecordGamerFromEvening($i)
	{
		$data = $this->nearEveningGetData(array('id','gamers','gamers_info'));
		if ($data === false) return false;
		$data['gamers'] = explode(',',$data['gamers']);
		$data['gamers_info'] = json_decode($data['gamers_info'], true);

		unset($data['gamers'][$i]);
		unset($data['gamers_info'][$i]);
		$this->rowUpdate(['gamers'=>implode(',',$data['gamers']),'gamers_info'=>json_encode($data['gamers_info'],JSON_UNESCAPED_UNICODE)],['id'=>$data['id']],SQL_TBLEVEN);
	}
	function CalculatePoints(&$p,&$v)
	{
		$ps = $this->modifySettingsArray($this->settingsGet(['type','short_name','name','value'],'point'));
		$maf_dops = explode(',',$ps['point']['maf_dops']['value']);
		$mir_dops = explode(',',$ps['point']['mir_dops']['value']);
		$bm_dops = explode(',',$ps['point']['bm']['value']);
		
		$points = 0.0;
		$i = -1;
		$m = $g = 0;
		$c = count($p);
		while(++$i<$c)
		{
			if($p[$i]['out'] > 0) continue;
			++$g;
			if ($p[$i]['role'] === 1 || $p[$i]['role'] === 2)
				++$m;
		}
		$i = -1;
		while(++$i<$c)
		{
			$p[$i]['points'] = ($v['win'] == 1 && ($p[$i]['role'] == 0 || $p[$i]['role'] == 4) ? $ps['point']['win']['value'] : 0) +
				($v['win'] == 2 && ($p[$i]['role'] == 1 || $p[$i]['role'] == 2) ? $ps['point']['win']['value'] : 0) +
				(isset($v['dops'][$i]) ? $v['dops'][$i] : 0) +
				($i == $v['make_bm'] && count($v['bm']) > 0 || $p[$i]['role'] == 4 ? $bm_dops[$this->check_bm($v['bm'],$p)] : 0) +
				($p[$i]['role'] == 2 && count($v['kill'][0]) == 1 && $p[$v['kill'][0][0]]['role'] == 4 ? $ps['point']['fk_sheriff']['value'] : 0.0) + 
				($v['win'] == 2 && $g <= 6 && ($p[$i]['role'] == 1 || $p[$i]['role'] == 2) && $p[$i]['out'] == 0 ? $maf_dops[$m] : 0.0) +
				($v['win'] == 1 && $g <= 3 && ($p[$i]['role'] == 0 || $p[$i]['role'] == 4) && $p[$i]['out'] == 0 ? $mir_dops[$g] : 0.0) +
				($p[$i]['fouls'] == 5 ? $ps['point']['fouls']['value'] : 0.0);
		}
		return $p;
	}
	function check_bm($bm,&$p)
	{
		$i =-1;
		$m = 0;
		while(++$i<count($bm))
			$m += ($p[$bm[$i]]['role'] == 1 || $p[$bm[$i]]['role'] == 2) ? 1 : 0;
		return $m;
	}
	// Работа с комментариями
	function GetComments($t,$id)
	{
		if (count($r = $this->getAssocArray($this->query('SELECT `author`,`txt` FROM `'.SQL_TBLCOMM.'` WHERE `target` ="'.$id.'" AND `type`="'.$t.'" ORDER BY `id` DESC'))) > 0)
			return $r;
		else
			return false;
	}
	// Добавление/изменение новости
	function setNews($a)
	{
		unset($a['need']);
		if (isset($a['date_remove']));
			$a['date_remove'] = strtotime($a['date_remove']);
		if (isset($a['id']))
		{
			$this->rowUpdate($a,array('id'=>$a['id']),SQL_TBLNEWS);
			return $a['id'];
		}
		$a['date_add'] = $_SERVER['REQUEST_TIME'];
		return $this->rowInsert($a,SQL_TBLNEWS);
	}
}