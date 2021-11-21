<?php
if (!defined('VOTES_LOAD'))
	define('VOTES_LOAD',true);
class VoteSystem extends SQLBase
{
	// Голосования и голоса хранятся в одной таблице.
	// у голосований:
	// object - id того, по кому ведётся голосование (Игрок, место)
	// type - тип голосования: rank - изменение категории; status - изменение статуса;
	// У голосов:
	// object - id голосования
	// type - Вид голоса: 'against' - Голос "Против"; 'for' - Голос "За";
	// Голоса от голосования в таблице различаются по "Типу": от 0 до 9 - голосования по различным поводам, а 10-11 - Голоса За и Против
	function GetVoteData($c,$b)
	{
		return $this->getAssoc($this->query('SELECT `'.implode('`,`',$c).'` FROM `'.SQL_TBLVOTES.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	function GetVotes($id)
	{
		return $this->getAssocArray($this->query('SELECT `author`,`txt`,`type` FROM `'.SQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")'));
	}
	function AddVoteEvent($a)
	{
		return $this->rowInsert($a,SQL_TBLVOTES);
	}
	function VoteIsOver($id)
	{
		$this->rowUpdate(array('open'=>0),array('id'=>$id));
	}
	function CheckVoteInAction($id,$type)
	{
		if ($r = $this->query('SELECT `id` FROM `'.SQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type`="'.$type.'" AND `open`="1" LIMIT 1'))
			return $this->getRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
	function CheckVoteGoal($id) 
	{
		//-----------------Переделать на 2 прямых запроса: count(`id`) WHERE `object`="'.$id.'" AND `type` = "against" и отдельно для `type` = "for"
		if ($r = $this->getRawArray($this->query('SELECT `type` FROM `'.SQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")')))
		{
			$votes=array('against'=>0,'neutral'=>0,'for'=>0);
			$cap = (($this->getRawArray($this->query('SELECT count(`id`) FROM `'.SQL_TBLUSERS.'` WHERE `status` > 0'))[0])/2)+1;
			for($x=0;$x<count($r);$x++)
				++$votes[$r[$x]];
			if ($votes['against'] > $cap || $votes['for'] > $cap)
			{
				$this->VoteIsOver($id);
				return true;
			}
			else return false;
		}
		else error_log(__METHOD__.': SQL ERROR');
	}
	function CheckUserVotes($id,$v) 
	{
		if ($r = $this->query('SELECT `id` FROM `'.SQL_TBLVOTES.'` WHERE `object`="'.$v.'" AND `author`="'.$id.'" AND `type` IN ("negative","neutral","positive") LIMIT 1'))
			return $this->getRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
}