<?php
if (!defined('JSFUNC_LOAD'))
	define('JSFUNC_LOAD',true);
class JSFunc extends SQLBase
{
	protected $SQL;
	function GetNamesAutoComplete($s,$e=0) 
	{
		$dop = '';
		if ($e > 0) $dop = ' AND `id` IN ('.$this->GetEveningGamers($e).')';
		if ($r = $this->Query('SELECT `name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `name` LIKE "%'.$s.'%"'.$dop))
			return $this->MakeSimpleString($r,'","');
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetPlacesAutoComplete($s,$z='') 
	{
		if ($r = $this->Query('SELECT `pl_name` FROM `'.MYSQL_TBLPLACES.'` WHERE `pl_name` LIKE "%'.$s.'%"'))
			return $this->MakeSimpleString($r,'","');
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetPlacesInfo($s) 
	{
		if ($r = $this->Query('SELECT `pl_info` FROM `'.MYSQL_TBLPLACES.'` WHERE `pl_name` = "'.$s.'" LIMIT 1'))
			return $this->MakeRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetResidentsNames($c=11)
	{
		if ($r = $this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `status` > 0 ORDER BY `id` LIMIT '.$c))
			return $this->MakeAssocArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetRandomPlayers($c=10,$e=-1) 
	{
		// Переделать, опираясь на новую колонку "gamers_info"
		if ($e !== -1) $dop = $this->MakeRawArray($this->Query('SELECT `gamers` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
		if ($r = $this->Query('SELECT `name` FROM `'.MYSQL_TBLGAMERS.'` '.(isset($dop) ? 'WHERE `id` IN ('.$dop.')' : 'ORDER BY RAND() LIMIT '.$c)))
			return $this->MakeRawArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetAllEveningGames($e_id)
	{
		return $this->GetAllGames($this->GetEveningGames($e_id));
	}
	function GetAllGamesByDate($d)
	{
		return $this->GetAllGames($this->GetEveningGames($this->GetEveningID($d)));
	}
	function GetAllGames($ids=0) 
	{
		if ($r = $this->Query('SELECT `id`,`players`,`vars`,`win`,`manager`,`rating`,`g_ids` FROM `'.MYSQL_TBLGAMES.'` WHERE `win` > 0'.($ids !== 0 ? ' AND `id` IN ('.$ids.')' : '')))
			return $this->MakeAssocArray($r);
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
		if ($r = $this->Query('SELECT `games` FROM `'.MYSQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->MakeSimpleString($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	function GetAllGamersIDs($f_date=0,$t_date=0) 
	{
		if ($f_date !== 0) $dop = ' AND `date` >= '.$f_date;
		if ($t_date !== 0) $dop = (isset($dop) ? $dop : '').' AND `date` <= '.$t_date;
		if ($r = $this->Query('SELECT `players` FROM `'.MYSQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->MakeSimpleString($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	// Получение информации об вечерах игры по заданым критериям:
	// $f - метка времени с какой даты
	// $t - метка времени по какую дату
	function GetAllEveningsData($f=0,$t=0) 
	{
		if ($f !== 0) $dop = ' AND `date` >= '.$f;
		if ($t !== 0) $dop = (isset($dop) ? $dop : '').' AND `date` <= '.$t;
		if ($r = $this->Query('SELECT `games`,`gamers` FROM `'.MYSQL_TBLEVEN.'` WHERE `id` > 0'.(isset($dop) ? $dop : '')))
			return $this->MakeAssocArray($r);
		else 
		{
			error_log(__METHOD__.': SQL ERROR');
			return false;
		}
	}
	function GetAllGamers($e=-1) 
	{
		if ($e !== -1) $dop = 'WHERE `id` IN ('.$this->GetEveningGamers($e).')';
		if ($r = $this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` '.(isset($dop) ? $dop : '')))
			return $this->MakeSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function GetEveningGamers($e)
	{
		return $this->MakeRawArray($this->Query('SELECT `gamers` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
	}
	function GetEveningGames($e)
	{
		return $this->MakeRawArray($this->Query('SELECT `games` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
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
		return $this->GetGamersIDs($a);
	}
	function getUsersIDs($gamers){
		$a = [];
		$gamers = json_decode($gamers,true);
		for ($x=0;$x<count($gamers);$x++){
			$check = trim($gamers[$x]['name']);
			if ($check === '')
			{
				unset($gamers[$x]);
				continue;
			}
			$gamers[$x]['name'] = $check !== '+1' ? $check : 'tmp_user_'.$x;
		}
		$gamers = array_values($gamers);
		for ($x=0;$x<count($gamers);$x++){
			$a[$x]['id'] = -1;
			$a[$x]['name'] = $gamers[$x]['name'];
			$a['enum'] .= '"'.$gamers[$x]['name'].'",';
		}
		$a['enum']= substr($a['enum'],0,-1);
		return $this->GetGamersIDs($a);
	}
	function CheckEveningID($i)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLEVEN.'` WHERE `id` ="'.$i.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function GetNearEveningData($c = 'id')
	{
		if (!is_array($c))
		{
			$r = $this->MakeRawArray($this->Query('SELECT `'.$c.'` FROM `'.MYSQL_TBLEVEN.'` WHERE `date` >="'.($_SERVER['REQUEST_TIME']-DATE_MARGE).'" ORDER BY `id` DESC LIMIT 1'));
			if (isset($r[0]) && $r[0] != '') return $r[0];
		}
		else
		{
			$r = $this->GetAssoc($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLEVEN.'` WHERE `date` >="'.($_SERVER['REQUEST_TIME']-DATE_MARGE).'" ORDER BY `id` DESC LIMIT 1'));
			if (isset($r) && count($r) > 0) return $r;
		}
		return false;
	}
	function GetEveningData($c,$b)
	{
		return $this->GetAssoc($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLEVEN.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	function GetEveningID($d)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLEVEN.'` WHERE `date` BETWEEN '.$d.' AND '.($d+82800).' LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function GetEveningDate($id)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") FROM `'.MYSQL_TBLEVEN.'` WHERE `id` ="'.$id.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	function GetLastEveningInfo()
	{
		if ($r = $this->GetAssoc($this->Query('SELECT `id`,DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") AS `date`,`games`,`gamers` FROM `'.MYSQL_TBLEVEN.'` ORDER BY `id` DESC LIMIT 1')))
			return $r;
		else return false;
	}
	function setEveningApproved($data)
	{
		if ($data['place']!=='') $r = $this->GetPlaceData($data['place'],$data['p_info']);
		else $r['id'] = 0;
		$a = array('date'=>$data['date'],'place'=>$r['id'],'applied'=>1);
		if (isset($data['gamers']))
		{
			$a['gamers'] = $this->getUsersIDs($data['gamers'])['ids'];
			$a['gamers_info'] = $data['gamers'];
		}
		$eveningId = $this->GetNearEveningData();
		if ($eveningId === false)
			$this->InsertRow($a,MYSQL_TBLEVEN);
		else 
			$this->UpdateRow($a,array('id'=>$eveningId),MYSQL_TBLEVEN);
	}
	function SetEveningID($d,$p)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLEVEN.'` WHERE `date` ="'.$d.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return $this->InsertRow(array('date'=>$d,'gamers'=>$p),MYSQL_TBLEVEN);
	}
	function GetPlaceByID($id)
	{
		if ($r = $this->GetAssoc($this->Query('SELECT `id`,`pl_name` AS `name`,`pl_info` AS `info` FROM `'.MYSQL_TBLPLACES.'` WHERE `id` = '.$id.' LIMIT 1')))
			return $r;
		else return false;
	}
	function GetPlaceData($p,$i)
	{
		if (($r = $this->GetAssoc($this->Query('SELECT `id`,`pl_name` AS `place`,`pl_info` AS `place_info` FROM `'.MYSQL_TBLPLACES.'` WHERE `pl_name` = "'.$p.'" LIMIT 1')))['id'] > 0)
		{
			if ($i !== '' && $i !== $r['place_info'])
				$this->UpdateRow(array('pl_info'=>$i),array('id'=>$r['id']),MYSQL_TBLPLACES);
			return $r;
		}
		else return array('id'=>$this->InsertRow(array('pl_name'=>$p,'pl_info'=>$i),MYSQL_TBLPLACES), 'place'=>$p, 'place_info'=>$i);
	}
	function GameExists($id)
	{
		if ($this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLGAMES.'` WHERE `id` = "'.$id.'" AND `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0] == $id)
			return true;
		else return false;
	}
	function TryResumeGame() 
	{
		return ($_SESSION['id_game'] = $this->GetRow($this->Query('SELECT `id` FROM `'.MYSQL_TBLGAMES.'` WHERE `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0]);
	}
	function ResumeGame($g_id) 
	{
		if ($r = $this->GetAssoc($this->Query('SELECT `id`,`players`,`vars`,`manager`,`rating`,`win`,`g_ids`,`e_id`,`start` FROM `'.MYSQL_TBLGAMES.'` WHERE '.($g_id > 0 ? '`id`="'.$g_id.'"' : '`win`<1').' ORDER BY `id` DESC LIMIT 1')))
			$r = str_replace(array('»','}\",\"{','[\"','\"]'),array('"','}","{','["','"]'),$r);
		return $r;
	}
	function GetGameNum($e,$g)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `games` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))) !== false)
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
		$_SESSION['id_game'] = $this->InsertRow([
			'e_id'=>$e,
			'g_ids'=>$ids,
			'manager'=>$this->GetGamerID($m),
			'players'=>json_encode($players,JSON_UNESCAPED_UNICODE),
			'rating'=>$this->GetGameRating($ids),
			'vars'=>json_encode($this->getDefaultVars(),JSON_UNESCAPED_UNICODE),
		]);
		$games = $this->GetEveningGames($e);
		$games = $games == '' ? $_SESSION['id_game'] : $games.','.$_SESSION['id_game'];
		$this->UpdateRow(array('games'=>$games), array('id'=>$e), MYSQL_TBLEVEN);
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
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLGAMERS.'` WHERE `name` ="'.$n.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return $chk === 0 ? $this->InsertRow(array('name'=>$n),MYSQL_TBLGAMERS) : false;
	}
	function GetGamersNames($ids,$s = false)
	{
		if (count($r = $this->MakeAssocArray($this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `id` IN ('.$ids.') ORDER BY `status` DESC'))) > 0)
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
		if (($r = $this->MakeRawArray($this->Query('SELECT `rank` FROM `'.MYSQL_TBLGAMERS.'` WHERE `id` IN ('.$ids.') LIMIT 20'))) !== false)
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
		$ids = $this->GetEveningGamers($e).','.$id;
		$this->UpdateRow(array('gamers'=>$ids),array('id'=>$e),MYSQL_TBLEVEN);
		return $ids;
	}
	function RemoveGamerFromEvening($e,$id)
	{
		$ids = str_replace(array($id.',',','.$id),array('',''),$this->GetEveningGamers($e));
		$this->UpdateRow(array('gamers'=>$ids),array('id'=>$e),MYSQL_TBLEVEN);
	}
	function UnRecordGamerFromEvening($i)
	{
		$data = $this->GetNearEveningData(array('id','gamers','gamers_info'));
		if ($data === false) return false;
		$data['gamers'] = explode(',',$data['gamers']);
		$data['gamers_info'] = json_decode($data['gamers_info'], true);

		unset($data['gamers'][$i]);
		unset($data['gamers_info'][$i]);
		$this->UpdateRow(['gamers'=>implode(',',$data['gamers']),'gamers_info'=>json_encode($data['gamers_info'],JSON_UNESCAPED_UNICODE)],['id'=>$data['id']],MYSQL_TBLEVEN);
	}
	function GetGamersIDs($a)
	{
		$res = $this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `name` IN ('.$a['enum'].') LIMIT 25');
		unset($a['enum']);
		while ($row = $this->GetAssoc($res))
		{
			$i = -1;
			while(isset($a[++$i]))
				if ($a[$i]['name'] === $row['name'])
				{
					$a[$i]['id'] = (int) $row['id'];
					break;
				}
		}
		$i = -1;
		while(isset($a[++$i]))
		{
			if (trim($a[$i]['name']) === '') continue;
			if ($a[$i]['id'] === -1)
				$a[$i]['id'] = $this->InsertRow(array('name'=>$a[$i]['name']),MYSQL_TBLGAMERS);
			$a['ids'] .= 	$a[$i]['id'].',';
		}
		$a['ids'] = substr($a['ids'],0,-1);
		return $a;
	}
	function CalculatePoints(&$p,&$v)
	{
		$ps = $this->ModifySettingsArray($this->GetSettings(['type','shname','name','value'],'point'));
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
		if (count($r = $this->MakeAssocArray($this->Query('SELECT `author`,`txt` FROM `'.MYSQL_TBLCOMM.'` WHERE `target` ="'.$id.'" AND `type`="'.$t.'" ORDER BY `id` DESC'))) > 0)
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
			$this->UpdateRow($a,array('id'=>$a['id']),MYSQL_TBLNEWS);
			return $a['id'];
		}
		$a['date_add'] = $_SERVER['REQUEST_TIME'];
		return $this->InsertRow($a,MYSQL_TBLNEWS);
	}
}