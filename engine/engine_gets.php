<?php
if (!defined('GETS_LOAD'))
	define('GETS_LOAD',true);
//Класс для простого получения информации из базы, когда требуется получить самый минимум от базы, и до конца работы скрипта - ничего не надо будет записывать. 
class GetDatas extends SQLBase
{
	
	// Получение информации нескольких игр по их ID, если значение - не передано, то выбираются все игры из базы
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
	// Получение информации об месте игры по его ID в системе
	function GetPlaceByID($id)
	{
		if ($r = $this->getAssoc($this->query('SELECT `id`,`pl_name` AS `name`,`pl_info` AS `info` FROM `'.SQL_TBLPLACES.'` WHERE `id` = '.$id.' LIMIT 1')))
			return $r;
		else return false;
	}
	// Массовое получение имён игроков по их ids
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
	// Получить именя резидентов $c - количество
	function GetResidentsNames($c=11)
	{
		if ($r = $this->query('SELECT `id`,`name` FROM `'.SQL_TBLUSERS.'` WHERE `status` > 0 ORDER BY `id` LIMIT '.$c))
			return $this->getAssocArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	// Получение информации от системы голосований
	function GetVoteData($c,$b)
	{
		return $this->getAssoc($this->query('SELECT `'.implode('`,`',$c).'` FROM `'.SQL_TBLVOTES.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	// Получение информации о уже проголосовавших по текущему голосованию
	function GetVotes($id)
	{
		return $this->getAssocArray($this->query('SELECT `author`,`txt`,`type` FROM `'.SQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")'));
	}
	// Получение информации о голосованиях, в которых имеет право учавствовать пользователь, но ещё не проголосовал
	// $c === false - получить только количество
	// $c = array() - получить данные по голосованиям
	function GetUnvotedVotings($c=false)
	{
		$columns = $c === false ? 'count(`id`)' : '`'.implode('`,`',$c).'`';
		$func = ($c === false || is_array($c) && count($c) === 1) ? 'getRawArray' : 'getAssocArray';
		return $this->$func($this->query('SELECT '.$columns.' FROM `'.SQL_TBLVOTES.'` WHERE `type` NOT IN ("negative","neutral","positive") AND `open` = 1 AND `id` NOT IN (SELECT `object` FROM `'.SQL_TBLVOTES.'` WHERE `type` IN ("negative","neutral","positive") AND `author` = "'.$_SESSION['id'].'")'));
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
		return $this->getAssocArray($this->query(str_replace(array('FIRSTTABLE','SECONDTABLE'),array(SQL_TBLVOTES,SQL_TBLUSERS),$sql)));
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
		                	    <span><a href="/?profile='.$voted[$x]['author'].'">'.$this->getGamerName($voted[$x]['author']).'</a>:</span><span>'.($voted[$x]['txt']=== '' ? '<i>Без комментариев</i>' : $voted[$x]['txt']).'</span>
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
		if (count($r = $this->getAssocArray($this->query('SELECT `author`,`txt` FROM `'.SQL_TBLCOMM.'` WHERE `target` ="'.$id.'" AND `type`="'.$t.'" ORDER BY `id` DESC'))) > 0)
			return $r;
		else
			return false;
	}
	function GameExists($id)
	{
		if ($this->getRawArray($this->query('SELECT `id` FROM `'.SQL_TBLGAMES.'` WHERE `id` = "'.$id.'" AND `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0] == $id)
			return true;
		else return false;
	}
	function TrygameResume() 
	{
		return ($_SESSION['id_game'] = $this->getRow($this->query('SELECT `id` FROM `'.SQL_TBLGAMES.'` WHERE `win`<1 AND `players` !="" ORDER BY `id` DESC LIMIT 1'))[0]);
	}
	// Получить информацию обо всех новостях
	function GetNewsData($c,$b='',$l=1)
	{
		$method = ($l !== 1) ? 'getAssocArray' : 'getAssoc';
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
		return $this->$method($this->query('SELECT `'.implode('`,`',$c).'` FROM `'.SQL_TBLNEWS.'`'.$where.' ORDER BY `id` DESC'.($l !== 0 ? ' LIMIT '.$l : '')));
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
		return $this->getRawArray($this->query('SELECT count(`id`) FROM `'.SQL_TBLNEWS.'`'.$where))[0];
	}
}