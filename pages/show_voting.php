<?
if (!defined('GETS_LOAD'))
{
	include 'dir_cfg.php';
	$engine_set = 'GETS';
	include $root_path.'/engine/engine.php';
	$engine = new GetDatas();
}
$html = '';
if (!isset($_POST['op'])) 
	$html = '<div class="vote_tabs_row">
		<span class="span_tabs" id="ClosedVotings">Завершённые</span>
		<span class="span_tabs active" id="OpenVotings">Активные</span>
	</div>
	<div id="AllVotings">';
$votings = $engine->GetAllVotingsData(isset($_POST['op']) ? $_POST['op'] : '1');
$my_votes = $engine->GetUnvotedVotings(array('id'));
for($x=0;$x<count($votings);$x++){
	$voted = $engine->GetVotes($votings[$x]['vote_id']);
	$html .= '<div class="VotingHeaderDiv">
		<span class="VotingMyStatus">'.(in_array($votings[$x]['vote_id'],$my_votes) ? 'Ещё не голоснул!' : 'Проголосовал!').'</span>
		<span class="VotingName">'.$votings[$x]['name'].'</span>
		<span class="VotingStart">'.date('H:i:s d.m.Y',strtotime($votings[$x]['started'])).'</span>
	</div>
	<div class="VotingInfo" id="'.$votings[$x]['vote_id'].'" style="display:none">
		<div class="VotingInfoCaption">Уже проголосовали:</div>
		<div id="PrevVotes">';
		$classes = array('negative','positive');
		$positive = '<div class="vote_lists positive_list">';
		$negative = '<div class="vote_lists negative_list">';
		$i=0;
		for($y=0;$y<count($voted);$y++){
			if ($voted[$y]['type'] === '11')
			{
				++$i;
				$positive .= '<div class="user_voted vote_'.($classes[$voted[$y]['type']-10]).'">'.$i.'. <b>'.$engine->GetPlayerName($voted[$y]['author']).'</b> : '.$voted[$y]['txt'].'</div>';
			}
		}
		$i=0;
		for($y=0;$y<count($voted);$y++)
		{
			if ($voted[$y]['type'] === '10')
			{
				++$i;
				$negative .= '<div class="user_voted vote_'.($classes[$voted[$y]['type']-10]).'">'.$i.'. <b>'.$engine->GetPlayerName($voted[$y]['author']).'</b> : '.$voted[$y]['txt'].'</div>';
			}
		}
		$html .= $positive.'</div>'.$negative.'</div>
		</div>';
		if (in_array($votings[$x]['vote_id'],$my_votes))
		{
			$html .= '<hr>
			<div class="vote_txt_row">Желаете как-то прокомментировать Ваше решение?</div>
			<textarea id="comm_'.$votings[$x]['vote_id'].'" name="vote_comment" rows="2" placeholder="Можно и без комментариев, но может это поможет другим определиться?">
			</textarea>
			<div class="vote_button_row">
				<span class="span_button span_vote" id="MyVoteFor"><img src="'.$settings['img']['apply']['value'].'"/>За<img src="'.$settings['img']['apply']['value'].'"/></span>
				<span class="span_button span_vote" id="MyVoteAgainst"><img src="'.$settings['img']['cancel']['value'].'"/>Против<img src="'.$settings['img']['cancel']['value'].'"/></span>
			</div>';
		}
	$html .= '</div>';
}
if (!isset($_POST['t']))
	$html .= '</div>';
echo $html;