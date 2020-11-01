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
		return $this->GetAssoc($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLVOTES.'` WHERE `'.array_keys($b)[0].'`="'.array_values($b)[0].'" LIMIT 1'));
	}
	function GetVotes($id)
	{
		return $this->MakeAssocArray($this->Query('SELECT `author`,`txt`,`type` FROM `'.MYSQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")'));
	}
	function AddVoteEvent($a)
	{
		return $this->InsertRow($a,MYSQL_TBLVOTES);
	}
	function VoteIsOver($id)
	{
		$this->UpdateRow(array('open'=>0),array('id'=>$id));
	}
	function CheckVoteInAction($id,$type)
	{
		if ($r = $this->Query('SELECT `id` FROM `'.MYSQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type`="'.$type.'" AND `open`="1" LIMIT 1'))
			return $this->MakeRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
	function CheckVoteGoal($id) 
	{
		//-----------------Переделать на 2 прямых запроса: count(`id`) WHERE `object`="'.$id.'" AND `type` = "against" и отдельно для `type` = "for"
		if ($r = $this->MakeRawArray($this->Query('SELECT `type` FROM `'.MYSQL_TBLVOTES.'` WHERE `object`="'.$id.'" AND `type` IN ("negative","neutral","positive")')))
		{
			$votes=array('against'=>0,'neutral'=>0,'for'=>0);
			$cap = (($this->MakeRawArray($this->Query('SELECT count(`id`) FROM `'.MYSQL_TBLGAMERS.'` WHERE `status` > 0'))[0])/2)+1;
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
		if ($r = $this->Query('SELECT `id` FROM `'.MYSQL_TBLVOTES.'` WHERE `object`="'.$v.'" AND `author`="'.$id.'" AND `type` IN ("negative","neutral","positive") LIMIT 1'))
			return $this->MakeRawArray($r)[0];
		else error_log(__METHOD__.': SQL ERROR');
	}
}