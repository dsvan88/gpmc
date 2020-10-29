<?php
if (!defined('GETS_LOAD'))
	define('GETS_LOAD',true);
//Класс для простого получения информации из базы, когда требуется получить самый минимум от базы, и до конца работы скрипта - ничего не надо будет записывать. 
class GetDatas extends SQLBase
{
	// Получить ID вечера по дате
	function GetEveningID($d)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLEVEN.'` WHERE `date` ="'.$d.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	// Получить дату вечера по его ID
	function GetEveningDate($id)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") FROM `'.MYSQL_TBLEVEN.'` WHERE `id` ="'.$id.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	// Простая проверка наличия ID вечера в базе
	function CheckEveningID($i)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `id` FROM `'.MYSQL_TBLEVEN.'` WHERE `id` ="'.$i.'" LIMIT 1'))[0]) > 0)
			return $r;
		else return false;
	}
	// Получить информацию про последний состоявшийся вечер игр
	function GetLastEveningInfo()
	{
		$r = $this->GetAssoc($this->Query('SELECT `id`,DATE_FORMAT(DATE(FROM_UNIXTIME(`date`)),"%d.%m.%Y %H:%i") AS `date`,`games`,`gamers`,`gamers_info` FROM `'.MYSQL_TBLEVEN.'` ORDER BY `id` DESC LIMIT 1'));
		if (count($r) > 0)
			return $r;
		else return false;
	}
	// Получение информации об ближайшем вечере игры
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
	// Получение информаций по всем играм за конкретный вечер
	function GetAllEveningGames($e_id)
	{
		return $this->GetAllGames($this->GetEveningGames($e_id));
	}
	// Получение списка игр за конкретный вечер
	function GetEveningGames($id)
	{
		return $this->MakeRawArray($this->Query('SELECT `games` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$id.'" LIMIT 1'))[0];
	}
	// Получение информации об вечере игры по заданым критериям:
	// $c - обычный массив из полей, которые нас интересуют
	// $b - ассоциативный массив, где ключ - имя поля, которое нас интересует, знаечение - значение этого поля
	function GetEveningData($c,$b)
	{
		return $this->GetAssoc($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLEVEN.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	// Получение списка игроков, учасвстующих в $e вечере
	function GetEveningGamers($e)
	{
		return $this->MakeRawArray($this->Query('SELECT `gamers` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
	}
	// Получение простого списка игроков, принимающих участие в $e вечере
	function GetAllGamers($e=-1) 
	{
		if ($e !== -1) $dop = 'WHERE `id` IN ('.$this->GetEveningGamers($e).')';
		if ($r = $this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` '.(isset($dop) ? $dop : '')))
			return $this->MakeSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	// Получение списка из $c случайних игроков, принимающих участие в $e вечере
	function GetRandomPlayers($c=10,$e=-1) 
	{
		if ($e !== -1) $dop = $this->MakeRawArray($this->Query('SELECT `gamers` FROM `'.MYSQL_TBLEVEN.'` WHERE `id`="'.$e.'" LIMIT 1'))[0];
		if ($r = $this->Query('SELECT `name` FROM `'.MYSQL_TBLGAMERS.'` '.(isset($dop) ? 'WHERE `id` IN ('.$dop.')' : 'ORDER BY RAND() LIMIT '.$c)))
			return $this->MakeRawArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	// Получение информации нескольких игр по их ID, если значение - не передано, то выбираются все игры из базы
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
	// Получение информации об месте игры по его ID в системе
	function GetPlaceByID($id)
	{
		if ($r = $this->GetAssoc($this->Query('SELECT `id`,`pl_name` AS `name`,`pl_info` AS `info` FROM `'.MYSQL_TBLPLACES.'` WHERE `id` = '.$id.' LIMIT 1')))
			return $r;
		else return false;
	}
	// Получение имени игрока по его ID в системе
	function GetGamerName($id)
	{
		if (($r = $this->MakeRawArray($this->Query('SELECT `name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `id` ="'.$id.'" LIMIT 1'))[0]) !== false)
			return $r;
		else return '';
	}
	// Массовое получение имён игроков по их ids
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
	// Получить именя резидентов $c - количество
	function GetResidentsNames($c=11)
	{
		if ($r = $this->Query('SELECT `id`,`name` FROM `'.MYSQL_TBLGAMERS.'` WHERE `status` > 0 ORDER BY `id` LIMIT '.$c))
			return $this->MakeAssocArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	// Получение информации от системы голосований
	function GetVoteData($c,$b)
	{
		return $this->GetAssoc($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLVOTES.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	// Получение информации о уже проголосовавших по текущему голосованию
	function GetVotes($id)
	{
		return $this->MakeAssocArray($this->Query('SELECT `author`,`txt`,`type` FROM `'.MYSQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")'));
	}
	// Получение информации о голосованиях, в которых имеет право учавствовать пользователь, но ещё не проголосовал
	// $c === false - получить только количество
	// $c = array() - получить данные по голосованиям
	function GetUnvotedVotings($c=false)
	{
		$columns = $c === false ? 'count(`id`)' : '`'.implode('`,`',$c).'`';
		$func = ($c === false || is_array($c) && count($c) === 1) ? 'MakeRawArray' : 'MakeAssocArray';
		return $this->$func($this->Query('SELECT '.$columns.' FROM `'.MYSQL_TBLVOTES.'` WHERE `type` NOT IN ("negative","neutral","positive") AND `open` = 1 AND `id` NOT IN (SELECT `object` FROM `'.MYSQL_TBLVOTES.'` WHERE `type` IN ("negative","neutral","positive") AND `author` = "'.$_SESSION['id'].'")'));
	}
	// Получение информации о голосованиях, в которых имеет право учавствовать пользователь, но ещё не проголосовал
	// $c === false - получить только количество
	// $c = array() - получить данные по голосованиям
	function GetAllVotingsData($o=1)
	{
		$sql = 'WITH `tmp_SECONDTABLE` AS
		(
			SELECT `id`, 
			`name`,
			`status`
			FROM `SECONDTABLE`
			WHERE `status` > 0
			GROUP BY `id`
		)
		SELECT 
			`FIRSTTABLE`.`id` AS `vote_id`,
			`FIRSTTABLE`.`object` AS `user_id`,
			`SECONDTABLE`.`name` AS `user_name`,
			`SECONDTABLE`.`status` AS `user_status`,
			`FIRSTTABLE`.`type`,
			`FIRSTTABLE`.`author` AS `author_id`,
			`tmp_SECONDTABLE`.`name` AS `author_name`,
			`tmp_SECONDTABLE`.`status` AS `author_status`,
			`FIRSTTABLE`.`name`,
			`FIRSTTABLE`.`started`,
			`FIRSTTABLE`.`open`
		FROM `FIRSTTABLE`
		LEFT JOIN `SECONDTABLE` ON `SECONDTABLE`.`id` = `FIRSTTABLE`.`object`
		LEFT JOIN `tmp_SECONDTABLE` ON `tmp_SECONDTABLE`.`id` = `FIRSTTABLE`.`author`
		WHERE `FIRSTTABLE`.`type` NOT IN ("negative","neutral","positive")
		'.($o>=0 ? 'AND `FIRSTTABLE`.`open` = '.$o : '').' 
		ORDER BY `FIRSTTABLE`.`id`';
		return $this->MakeAssocArray($this->Query(str_replace(array('FIRSTTABLE','SECONDTABLE'),array(MYSQL_TBLVOTES,MYSQL_TBLGAMERS),$sql)));
	}
	//-------- получить HTML-код для страницы голосований
	function getVotingListHTML($open=1){
		$result = '';
		$votings = $this->GetAllVotingsData($open);
		$my_votes = $this->GetUnvotedVotings(array('id'));
		for($x=0;$x<count($votings);$x++){
			$checkMyVote = count($my_votes) > 0 && in_array($votings[$x]['vote_id'],$my_votes);
			$voted = $this->GetVotes($votings[$x]['vote_id']);
			$result .= '
			<div class="vote-lists__item '.($votings[$x]['open'] === '1' ? 'open-vote' : 'closed-vote').'" data-vote-id="'.$votings[$x]['vote_id'].'">
				<div class="vote-lists__item-header" data-action-type="toggle-vote-list">
					<span>'.($checkMyVote ? 'Не голосовал!' : 'Проголосовал!').'</span>
					<span>'.$votings[$x]['name'].'</span>
					<span>'.date('H:i:s d.m.Y',strtotime($votings[$x]['started'])).'</span>
				</div>
				<div class="vote-lists__item-body"  style="display:none">
					<h3>Уже проголосовали:</h3>
					<div class="vote-lists__item-body__lists">';
					$positive = '<ol class="positive">';
					$negative = '<ol class="negative">';
					$i=0;
					for($x=0;$x<count($voted);$x++)
						${$voted[$x]['type']} .= '
						 	<li>
		                	    <span><a href="/?profile='.$voted[$x]['author'].'">'.$this->GetGamerName($voted[$x]['author']).'</a>:</span><span>'.($voted[$x]['txt']=== '' ? '<i>Без комментариев</i>' : $voted[$x]['txt']).'</span>
		                	</li>';
					$result .= $positive.'</ol>'.$negative.'</ol>
					</div>';
					if ($checkMyVote && $open === 1)
					{
						
						$result .= '
						<hr>
						<div class="my-vote">
							<h3>Желаете как-то прокомментировать Ваше решение?</h3>
							<textarea name="vote_comment" rows="2" placeholder="Можно и без комментариев, но может это поможет другим определиться?"></textarea>
							<div class="my-vote__buttons">
								<span class="span_button span_vote" data-action-type="set-vote" data-action-mode="positive">За</span>
								<span class="span_button span_vote" data-action-type="set-vote" data-action-mode="negative">Против</span>
							</div>
						</div>';
					}
				$result .= '
					</div>
				</div>';
		}
		return $result;
	}
	// Получить комментарии
	function GetComments($t,$id)
	{
		if (count($r = $this->MakeAssocArray($this->Query('SELECT `author`,`txt` FROM `'.MYSQL_TBLCOMM.'` WHERE `target` ="'.$id.'" AND `type`="'.$t.'" ORDER BY `id` DESC'))) > 0)
			return $r;
		else
			return false;
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
	// Получить информацию обо всех новостях
	function GetNewsData($c,$b='',$l=1)
	{
		$method = ($l !== 1) ? 'MakeAssocArray' : 'GetAssoc';
		$where = '';
		if ($b !== '')
		{
			$where = ' WHERE ';
			foreach($b as $k=>$v)
			{
				if (!is_array($v))
					$where .= '`'.$k.'` = "'.$v.'" AND ';
				else $where .= '`'.$k.'` IN ("'.implode('","',$v).'") AND ';
			}
			$where = mb_substr($where,0,-4);
		}
		return $this->$method($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLNEWS.'`'.$where.' ORDER BY `id` DESC'.($l !== 0 ? ' LIMIT '.$l : '')));
	}
	// Получить количество всех новостей, по заданным условиям.
	function GetNewsCount($b = '')
	{
		$where = '';
		if ($b !== '')
		{
			$where = ' WHERE ';
			foreach($b as $k=>$v)
			{
				if (!is_array($v))
					$where .= '`'.$k.'` = "'.$v.'" AND ';
				else $where .= '`'.$k.'` IN ("'.implode('","',$v).'") AND ';
			}
			$where = mb_substr($where,0,-4);
		}
		return $this->MakeRawArray($this->Query('SELECT count(`id`) FROM `'.MYSQL_TBLNEWS.'`'.$where))[0];
	}
}