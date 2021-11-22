<?php
$_POST['userId'] = (int) $_POST['userId'];
if (!isset($_SESSION['id']) || $_SESSION['id'] < 1){
	$output['vote'] = 1;
	$output['html'] .= '
		<h2 class="title">ОШИБКА</h2>
		<div class="info-row">Не авторизованные пользователи не могут голосовать за изменение '.($_POST['editTarget']=== 'rank' ? 'категории' : 'статуса').' игроков!</div>';
}
elseif ($_SESSION['id'] == $_POST['userId'] && ($_POST['editTarget']=== 'rank' || $_POST['editTarget']=== 'status')){
	$output['vote'] = 0;
	$output['html'] = '
		<h2 class="title">ОШИБКА</h2>
		<div class="info-row">Вы не можете голосовать за изменение '.($_POST['editTarget']=== 'rank' ? 'своей категории' : 'своего статуса').'!</div>';
}
else {
	$engine_set = 'VOTES';
	include $root_path.'/engine/engine.php'; 
	$engine = new VoteSystem();
	$types = array('rank'=>'Изменение <b>категории</b>','status'=>'Изменение <b>статуса</b>');
	$voteId = $engine->CheckVoteInAction($_POST['userId'],$_POST['editTarget']);
	if ($voteId > 0){
		$c = $engine->CheckUserVotes($_SESSION['id'],$voteId);
		if ($c > 0){
			$output['vote'] = 0;
			$output['html'] .= '
				<h2 class="title">ОШИБКА</h2>
				<div class="info-row">Вы уже проголосовали по этому голосованию!<br>Повторное голосование возможно только по завершению текущего!</div>';
		}
		else {
			$voted = $engine->GetVotes($voteId);
			$output['vote'] = 2;
			$output['html'] .='
				<form>
				<h2>Голосование за "'.$types[$_POST['editTarget']].' игрока <b>'.$engine->getGamerName($_POST['userId']).'"</b></h2>
				<input type="hidden" name="voteid" value="'.$voteId.'"/>
				<input type="hidden" name="type" value="'.$_POST['editTarget'].'"/>
				<div class="info-block">Голосование уже в процессе!<br>
					Уже проголосовали:
					<div class="info-block__lists">';
			$positive='<ol class="positive">';
			$negative='<ol class="negative">';
			$i=0;
			for($x=0;$x<count($voted);$x++)
				${$voted[$x]['type']} .= '
					<li>
						'.$engine->getGamerName($voted[$x]['author']).': '.($voted[$x]['txt']=== '' ? '<i>Без комментариев</i>' : $voted[$x]['txt']).'
					</li>';
			$output['html'] .= $positive.'</ol>'.$negative.'</ol>
					</div>
				<div class="input_row">
					<label>Ваш голос:</label>
					<select name="motion">
						<option value="positive"'.($_POST['voteMotion'] === 'positive' ? ' selected' : '').'>
							За!
						</option>
						<option value="negative"'.($_POST['voteMotion'] === 'negative' ? ' selected' : '').'>
							Против!
						</option>
					</select>				
				</div>
			<hr>
			<div class="input_row textareas">
				<label>Желаете прокомментировать?</label>
				<textarea name="html" rows="2" placeholder="Можно и без комментариев, но это может помочь другим определиться"></textarea>
			</div>
			<div class="input_row buttons">
				<button>
					'
					.$engine->inputImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
					'
					Проголосовать!
					'
					.$engine->inputImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
					'
				</button>
			</div>
			</form>';
		}
	}
	else
	{
		$output['vote'] = 1;
		$output['html'] .= '
		<form>
		<h2>Голосование за:<br>
		"'.$types[$_POST['editTarget']].' игрока '.$engine->getGamerName($_POST['userId']).'"</h2>
		<input type="hidden" name="player_id" value="'.$_POST['userId'].'"/>
		<input type="hidden" name="type" value="'.$_POST['editTarget'].'"/>
		<div class="info-row">
			<label>Хотите начать голосование за "'.$types[$_POST['editTarget']].'"?</label>
		</div>
		<div class="input_row">
			<label>Ваш голос:</label>
			<select name="motion">
				<option value="positive"'.($_POST['voteMotion'] === 'positive' ? ' selected' : '').'>
					За!
				</option>
				<option value="negative"'.($_POST['voteMotion'] === 'negative' ? ' selected' : '').'>
					Против!
				</option>
			</select>				
		</div>
		<hr>
		<div class="input_row textareas">
				<label>Желаете прокомментировать?</label>
				<textarea name="html" rows="2" placeholder="Можно и без комментариев, но это может помочь другим определиться"></textarea>
		</div>
		<div class="input_row buttons">
			<button>
				'
				.$engine->inputImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
				'
				Начать!
				'
				.$engine->inputImage($settings['img']['apply']['value'],['title'=>$settings['img']['apply']['name']]).
				'
			</button>
		</div>
		</form>';
	}
}