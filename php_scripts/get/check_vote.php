<?php
$engine_set = 'VOTES';
include $root_path.'/engine/engine.php'; 
$result=array(
	'vote' => 0,
	'html' => ''
);
$_POST['i'] = (int) $_POST['i'];
$_POST['t'] = (int) $_POST['t'];
$_POST['m'] = (int) $_POST['m'];
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1)
{
	$result['vote'] = 1;
	$result['html'] = '<div class="modal_vote_caption">ОШИБКА!</div><div class="modal_vote_txt">Не авторизованные пользователи не могут голосовать за изменение '.($_POST['t']===0 ? 'категории' : 'статуса').' игроков!</div>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
elseif ($_SESSION['id'] == $_POST['i'])
{
	$result['vote'] = 0;
	$result['html'] = '<div class="modal_vote_caption">ОШИБКА!</div><div class="modal_vote_txt">Вы не можете голосовать за изменение '.($_POST['t']===0 ? 'своей категории' : 'своего статуса').'!</div>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
$engine = new VoteSystem();
$motion = array('<b>Против</b> повышения категории','<b>За</b> повышение категории','<b>Против</b> повышения статуса','<b>За</b> повышение</b> статуса');
$types = array('Изменение <b>категории</b>','Изменение <b>статуса</b>');
$r = $engine->CheckVoteInAction($_POST['i'],$_POST['t']);
if ($r > 0)
{
	$c = $engine->CheckUserVotes($_SESSION['id'],$r);
	if ($c > 0)
	{
		$result['vote'] = 0;
		$result['html'] = '<div class="modal_vote_caption">ОШИБКА!</div><div class="modal_vote_txt">Вы уже проголосовали по этому голосованию!<br>Повторное голосование возможно только по завершению текущего!</div>';
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}
	$classes = array('negative','positive');
	$voted = $engine->GetVotes($r);
	$result['vote'] = 2;
	$result['html'] ='<div class="modal_vote_caption">Голосование за "'.$types[$_POST['t']].' игрока <b>'.$engine->GetGamerName($_POST['i']).'"</b></div>
	<input type="hidden" name="v_id" value="'.$r.'"/>
	<input type="hidden" name="type" value="'.$_POST['t'].'"/>
	<div class="modal_vote_txt">Голосование уже в процессе!<br>
	Уже проголосовали:
	<div id="PrevVotes">';
	$positive='<div class="vote_lists positive_list">';
	$negative='<div class="vote_lists negative_list">';
	$i=0;
	for($x=0;$x<count($voted);$x++)
	{
		if ($voted[$x]['type'] === '11')
		{
			++$i;
			$positive .= '<div class="user_voted vote_'.($classes[$voted[$x]['type']-10]).'">'.$i.'. <b>'.$engine->GetGamerName($voted[$x]['author']).'</b> : '.$voted[$x]['txt'].'</div>';
		}
	}
	$i=0;
	for($x=0;$x<count($voted);$x++)
	{
		if ($voted[$x]['type'] === '10')
		{
			++$i;
			$negative .= '<div class="user_voted vote_'.($classes[$voted[$x]['type']-10]).'">'.$i.'. <b>'.$engine->GetGamerName($voted[$x]['author']).'</b> : '.$voted[$x]['txt'].'</div>';
		}
	}
	$result['html'] .= $positive.'</div>'.$negative.'</div>
	</div>
	<div class="vote_txt_row"><div class="left_part">Ваш голос:</div><div class="right_part"><input type="hidden" name="motion" value="'.$_POST['m'].'"/>"'.$motion[$_POST['m']+(2*$_POST['t'])].'"</div>
	</div>
	<hr>
	<div class="vote_txt_row">Желаете как-то прокомментировать Ваше решение?</div>
	<textarea name="vote_comment" rows="2" placeholder="Можно и без комментариев, но может это поможет другим определиться?"></textarea></div>
	<div class="vote_button_row"><span class="span_button" id="MyVote"><img src="'.$settings['img']['apply']['value'].'"/>Проголосовать!<img src="'.$settings['img']['apply']['value'].'"/></span></div>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}
else
{
	$result['vote'] = 1;
	$result['html'] = '<div class="modal_vote_caption">Голосование за<br>"'.$types[$_POST['t']].' игрока <b>'.$engine->GetGamerName($_POST['i']).'"</b></div>
	<input type="hidden" name="p_id" value="'.$_POST['i'].'"/>
	<input type="hidden" name="type" value="'.$_POST['t'].'"/>
	<div class="modal_vote_txt">Хотите начать голосование за "'.$types[$_POST['t']].'"?
	<div class="vote_txt_row"><div class="left_part">Ваш голос:</div><div class="right_part"><input type="hidden" name="motion" value="'.$_POST['m'].'"/>"'.$motion[$_POST['m']+(2*$_POST['t'])].'"</div>
	</div>
	<hr>
	<div class="vote_txt_row">Желаете как-то прокомментировать Ваше решение?</div>
	<textarea name="vote_comment" rows="2" placeholder="Можно и без комментариев, но может это поможет другим определиться?"></textarea></div>
	<div class="vote_button_row"><span class="span_button" id="MyVote"><img src="'.$settings['img']['apply']['value'].'"/>Начать!<img src="'.$settings['img']['apply']['value'].'"/></span></div>';
	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
}