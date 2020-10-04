<?
session_start();
header('Content-Type: text/javascript');
?>
var prev_vars = [];
var prev_players = [];
var prev_text = [];
var max_stepbacks = 10;
var vars = {};
var MainTimer;
var load = false;
function check_next_stage(){
	let shooted = false;
	if ($('.for_kill').length > 0) shooted = check_shooting();
	if (vars['stage'] === 'first_night' || vars['stage'] === 'night' && !shooted || vars['stage'] === 'last_will' && vars['prev_stage'] === 'night')
		return 'morning';
	else if (vars['stage'] === 'morning' || (vars['stage'] === 'day_speaker' && vars['day_speaker'] > 0))
		return 'day_speaker';
	else if (vars['stage'] === 'day_speaker' && vars['day_speaker'] <= 0)
		return 'court';
	else if ((vars['stage'] === 'court' || vars['stage'] === 'debate') && vars['debater'] !== -1)
		return 'debate';
	else if ((vars['stage'] === 'court' || vars['stage'] === 'debate' || vars['stage'] === 'last_will' && vars['prev_stage'] !== 'night') && vars.currentVote.length === 0 && vars['last_will'].length === 0)
		return 'night';
	else if ((((vars['stage'] === 'court' || vars['stage'] === 'debate') || vars['stage'] === 'last_will')) && vars['last_will'].length > 0 ||
		vars['stage'] === 'night' && shooted)
		return 'last_will';
}
function save_state_for_undo(){
	if (prev_vars.length === max_stepbacks)
	{
		prev_vars.shift()
		prev_players.shift()
		prev_text.shift()
	}
	prev_vars.push(JSON.stringify(vars));
	prev_players.push(JSON.stringify(players));
	$('td#Undo').removeClass('disabled');
}
function next(){
	if (MafAct()) return;
	if (!load)
	{
		save_state_for_undo()
		let new_stage = check_next_stage();
		if (vars['stage'] !== new_stage)
		{
			vars['prev_stage'] = vars['stage'];
			vars['stage'] = new_stage;
		}
	}
	if (vars['stage'] === 'day_speaker' || vars['stage'] === 'morning')
		day_func();
	else if (vars['stage'] === 'court')
		Do_Court();
	else if (vars['stage'] === 'debate')
		debate_func();
	else if (vars['stage'] === 'last_will')
		Do_SayLastWord(vars['last_will'].shift());
	else if (vars['stage'] === 'night')
		night_func();
	if (!load)
		save_progress();
}
function Do_Court(d=0){
	change_active_speaker();
	if (check_day_fouls())
	{
		Do_ClearCourtRoom(1);
		return true;
	}
	set_PhaseState('Зал суда.BRПросьба убрать руки от стола, прекратить жестикуляцию и агитацию.BRНа '+(d===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+vars.currentVote.join(', '));
	alert('Уважаемые игроки, переходим в зал суда!\r\nНа '+(d===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+vars.currentVote.join(', '));
	let i=-1,vote_available=0,players_count=0,voted=[],cv=[],prev_max=0,debate=[],s='';
	if (vars.currentVote.length === 0)
	{
		alert('На голосование никто не выставлен. Голосование не проводится.');
		Do_ClearCourtRoom(1);
		return true;
	}
	else if (vars.currentVote.length === 1)
	{
		s = 'На голосование был выставлен лишь 1 игрок\r\n';
		if (vars['day_count'] > 0)
		{
			alert(s+'Наш город покидает игрок №'+vars.currentVote[0]+'!\r\nУ вас есть 1 минута для последней речи');
			gamer_out(vars.currentVote[0]-1,2);
		}
		else
			alert(s+'Этого недостаточно для проведения голосования. Наступает фаза ночи!')
		Do_ClearCourtRoom(1);
		return true;
	}
	vote_available = players_count = GetActivePlayers();
	i=-1;
	while(++i<vars.currentVote.length)
	{
		cv.push(vars.currentVote[i]);
		if (vote_available < 1) 
		{
			voted.push(0)
			s+='Игрок  № '+vars.currentVote[i]+"\tГолоса: 0BR";
			continue;
		}
		vote= i<vars.currentVote.length-1 ? parseInt(prompt(vars.currentVote[i]+'! Кто за то, что бы наш город покинул игрок под № '+vars.currentVote[i],'0')) : vote_available;
		s+='Игрок  № '+vars.currentVote[i]+"\tГолоса: "+vote+"BR";
		voted.push(vote);
		vote_available-=vote;
		if (vote===0 || i===0) continue;
		if (voted[prev_max]<vote)
		{
			prev_max = i;
			if (debate.length!==0) debate.length=0;
		}
		else if (voted[prev_max]===vote)
		{
			if (debate.length===0) debate.push(vars.currentVote[prev_max],vars.currentVote[i]);
			else debate.push(vars.currentVote[i]);
		}
	}
	s = 'Голоса распределились следующим образом:BR'+s;
	if (debate.length===0)
	{
		s+='Нас покидает Игрок под № '+vars.currentVote[prev_max]+'.BRУ вас прощальная минута.';
		gamer_out(vars.currentVote[prev_max]-1,2);
	}
	else
		s+='В нашем городе перестрелка. Между игроками под номерами: '+debate.join(', ');
	alert(s.replace(/BR/g,'\r\n'));
	$('td.for_vote').removeClass('for_vote');
	if (d > 0 && debate.length === vars.currentVote.length)
	{
		if (players_count > 4)
		{
			vote= parseInt(prompt('Кто за то, что все игроки под номерами: '+debate.join(', ')+' покинули стол?','0'));
			if ( vote > players_count/2)
			{
				s='Большинство ('+vote+' из '+players_count+') - за!BRИгроки под номерами: '+debate.join(', ')+' покидают стол.';
				i=-1;
				while(++i<debate.length)
					gamer_out(debate[i]-1,2);
			}
			else 
				s='Большинство ('+(players_count-vote)+' из '+players_count+') - против!BRНикто не покидает стол.';
		}
		else 
			s='При количестве игроков менее 5 нельзя поднять 2 и более игроков.BRНикто не покидает стол.';
		alert(s.replace(/BR/g,'\r\n'));
		debate.length=0;
	}
	if (debate.length > 0)
	{
		vars.currentVote=debate;
		Do_Debate();
	}
	else
		Do_ClearCourtRoom(1)
}
function Do_Debate(){
	change_active_speaker(vars.currentVote[++vars['debater']]-1);
	set_PhaseState('Фаза перестрелки.BRРечь игрока №'+(vars.currentVote[vars['debater']]));
	let check = setInterval(function() { 
		if (vars['debater'] !== -1)
		{
			if (vars['timer'] === 6000) 
			{
				vars['timer'] = 3000;
				$('span.stopwatch').text(inttotime(vars['timer']));
			}
		}
		else
		{
			clearInterval(check);
			Do_Court(1);
		}
	}, 50);
}
function Do_ClearCourtRoom(n=1){
	if (!load)
		vars.currentVote.length=0;
	hide_courtroom();
	$('td.for_vote').removeClass( 'for_vote' );
	$('td.puted').text('');
	change_active_speaker();
	if (n === 1) next();
}
function Do_PutHim(e,id=-1){
	let i = parseInt(e.closest('tr').attr('id').split('_')[0]);
	e = $('tr[id^="'+i+'_"] td.vote_num');
	if (vars['stage'] === 'finish')
		Do_AddDops(i);
	else if (vars['stage'] === 'last_will' && vars['b_bm'] && vars['bm'].length < 3)
		Do_BestMove(e,i);
	else if (vars['stage'] === 'day_speaker' || (vars['stage'] === 'morning' && vars['timer'] < 6000))
		Do_PutHimOnTheVote(e,i,id);
	else if (vars['stage'] === 'night')
		Do_ShootHim(i);
}
function Do_Foul(e){
	let i = parseInt(e.closest('tr').attr('id').split('_')[0]);
	if (players[i]['out']>0) return false;
	if (e.attr('id') !== 'foul_4')
	{
		players[i]['fouls']++;
		if (players[i]['fouls']===3)
		{
			players[i]['muted'] = 1;
			$('tr[id^="'+i+'_"] > td#foul_3 > img').css({'display':'block'});
			$('tr[id^="'+i+'_"] td.prim').text('Молчит');
		}
		if (players[i]['fouls']=== 4)
		{
			if (confirm('Игрок №'+(i+1)+' покидает стол по 4-му фолу?'))
			{
				$('tr#'+i+'_'+players[i]['id']+'>td#foul_1.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_2.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_3.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_4.foul').addClass('fail');
				gamer_out(i,3);
				timer_pause();
				save_log('Игрок №'+(i+1)+' свой 4-й фол и покидает игру!');
				MafAct();
				return true;
			}
			else --players[i]['fouls'];
		}
		$('tr#'+i+'_'+players[i]['id']+'>td#foul_'+players[i]['fouls']+'.foul').addClass('fail');
		save_log('Игрок №'+(i+1)+' свой '+players[i]['fouls']+' фол!');
	}
	else if (confirm('Игрок №'+(i+1)+' покидает стол по дисквалифицирующему фолу?'))
	{
		players[i]['fouls']=5;
		$('tr#'+i+'_'+players[i]['id']+'>td#foul_1.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_2.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_3.foul,tr#'+i+'_'+players[i]['id']+'>td#foul_4.foul').addClass('fail');
		gamer_out(i,4);
		save_log('Игрок №'+(i+1)+' покидает стол по дисквалифицирующему фолу!');
		MafAct();
	}
	else return false;
	MafAct();
}
function MafAct(w = 0){
	let maf=0,i=-1,left=0,s='';
	while(++i<=9)
	{
		if (players[i]['out']>0) continue;
		++left;
		if (players[i]['role']==1 || players[i]['role']==2) ++maf;
	}
	if (maf===0 || w === 1)
	{
		vars['win'] = 1;
		s = 'Мирный город!BRТеперь, Ваши дети могут спать спокойно';
	}
	else if (maf>=left-maf || w === 2)
	{
		vars['win'] = 2;
		s = "Мафию!BRТеперь, Ваши дети могут спать сыто и спокойно!";
	}
	if (s!=='')
	{
		timer_reset();
		i=-1;
		while(++i<=9)
		{
			if (players[i]['role']===0) continue;
			else if (players[i]['role']===1)
				$("tr#"+i+"_"+players[i]['id']).addClass('mafia');
			else if (players[i]['role']===2)
				$("tr#"+i+"_"+players[i]['id']).addClass('don');
			else
				$("tr#"+i+"_"+players[i]['id']).addClass('sherif');
		}
		$(".for_image,.events").removeClass("hide");
		set_PhaseState('Поздравляем с победой: '+s);
		alert("Поздравляем с победой: "+s.replace(/BR/g,'\r\n'));
		$('#timer td').addClass('disabled');
		vars['stage'] = 'finish';
		save_progress();
		return true;
	}
	return false;
}
function Do_PutHimOnTheVote(e,i,id=-1){
	if (players[i]['out']>0)
	{
		alert('Не принято!\r\nЗа столом нет такого игрока.');
		return false;
	}
	let act = id === -1 ? (vars['timer'] === 6000 ? vars['prev_active'] : vars['active']) : id;
	if (act === -1) return false;
	let td = $('tr[id^="'+act+'_"] td.puted');
	if (td.text() !== '' && i !== parseInt(td.text())-1) return false;
	++i;
	let check = vars.currentVote.indexOf(i);
	if (check === -1)
	{
		td.text(i);
		td.addClass( "for_vote" );
		e.addClass( "for_vote" );
		vars.currentVote.push(i);
		players[act]['puted'][vars['day_count']] = i;
		save_log('Игрок №'+(act+1)+' выставил игрока №'+i+' на голосование!');
	}
	else
	{
		if (players[act]['puted'][vars['day_count']] === i)
		{
			td.removeClass( "for_vote" ).text('');
			e.removeClass( "for_vote" );
			vars.currentVote.splice(check,1);
			players[act]['puted'][vars['day_count']] = -1;
			save_log('Ошибочное выставление. Отмена!');
		}
		else
		{
			alert('Не принято!\r\nУже выстален.');
			save_log('Игрок №'+(act+1)+' попытался выставить игрока №'+i+' на голосование.BRНе принято - уже выставлен!');
			return false;
		}
	}
	if (vars.currentVote.length>0)
		show_courtroom();
	else hide_courtroom();
}
function Do_BestMove(e,i){
	if (vars['make_bm'] != $('tr.active').attr('id').split('_')[0])
	{
		vars['b_bm'] = false;
		return false;
	}
	e.addClass( 'for_bm' );
	vars['bm'].push(i+1);
	$('#best_move').removeClass('hide');
	$('#bm').text('Игрока №'+(vars['make_bm']+1)+': '+vars['bm'].join(',')+'.');
	if (vars['bm'].length === 3)
	{
		if (confirm('Игрок №'+(vars['make_bm']+1)+' назвал, игроками мафии, игроков, под номерами: '+vars['bm'].join(',')+'?'))
			vars['b_bm'] = false;
		else
		{
			vars['bm'] = [];
			$('#bm').text('Игрока №'+(vars['make_bm']+1)+': - .');
		}
		$('td.for_bm').removeClass('for_bm');
	}
}
function Do_ShootHim(i){
	if ($('tr.active').length > 0)
		return false;
	vars['kill'][vars['day_count']].push(i);
	$('tr[id^="'+i+'_"]').addClass('for_kill');
}
function Do_AddDops(i){
	let points = prompt('Дополнительные баллы!\r\nНа Ваше усмотрение, сколько можно добавить баллов игроку №'+(i+1)+' ('+$('tr[id^="'+i+'_"] td.player_name').text()+')?','0.0')
	if (points && points != 0.0)
	{
		points = parseFloat(points);
		alert('Игроку №'+(i+1)+(points > 0.0 ? ' добавлено ' : ' назначен штраф в ')+points+' баллов рейтинга');
		vars['dops'][i] += points;
		save_progress();
	}
}
function Do_SayLastWord(lw){
	let str = '';
	if (players[lw]['out'] === 1 && check_can_make_bm(lw))
		str = 'BRУ вас есть право лучшего хода!';
	change_active_speaker(lw);
	set_PhaseState('Игрок № '+(lw+1)+' покидает стол.BRУ вас есть последнее слово!BRПрощальная минута игрока №'+(lw+1)+str);
}
function check_can_make_bm(lw){
	let check=0;
	let i=-1;
	while(++i<=9)
	{
		if (players[i]['out']===1 || players[i]['out']===2)
			++check;
		if (check > 1) break;
	}
	alert(check);
	if (check==1)
	{
		vars['make_bm'] = lw;
		vars['b_bm'] = true;
		alert(vars['make_bm']);
		return true;
	}
	return false;
}
function check_shooting(){
	$('tr.for_kill').removeClass('for_kill');
	if (vars['day_count'] >= 0)
	{
		if (vars['kill'][vars['day_count']].length === 1)
		{
			gamer_out(vars['kill'][vars['day_count']][0],1);
			return true;
		}
		else 
			alert('Промах! Никто не был убит этой ночью.');
	}
	return false;
}
function night_func(){
	change_active_speaker();
	active = -1;
	set_PhaseState('Фаза ночи.BRИгроки мафии стреляют.BRДон и шериф - делают проверки');
}
function day_func(){
	if (vars['stage'] === 'morning')
	{
		vars['kill'][++vars['day_count']]= [];
		let i = -1;
		while(++i<=9)
			players[i]['puted'][vars['day_count']] = -1;
		vars['day_speaker'] = GetActivePlayers();
		vars['active'] = NextSpeaker(vars['day_count']);
		if ($('.for_kill').length !== 1)
			$('.for_kill').removeClass('for_kill');
	}
	else 
	{
		vars['prev_active'] = vars['active'];
		vars['active'] = NextSpeaker(vars['active']+1);
	}
	change_active_speaker(vars['active']);
	set_PhaseState('Фаза дня.BRДень №'+vars['day_count']+'BRРечь игрока №'+(vars['active']+1));
}
function debate_func(){
	if (++vars['debater'] !== vars.currentVote.length)
	{
		change_active_speaker(vars.currentVote[vars['debater']]-1);
		set_PhaseState('Фаза перестрелки.BRРечь игрока №'+(vars.currentVote[vars['debater']]));
	}
	else
	{
		change_active_speaker();
		vars['debater'] = -1;
	}
}
function gamer_out(id,reason){
	$('tr[id^='+id+'_]').addClass('out');
	$('tr[id^='+id+'_] td.prim').text(reasons[reason]);
	players[id]['out'] = reason;
	if (reason < 3) vars['last_will'].push(id);
	else players[id]['muted'] = 1;
	save_log('Игрок №'+(id+1)+' покидает наш город. Причина: '+reasons[reason]+'!');
	return vars['last_will'];
}
function check_day_fouls(){
	let i = -1;
	while(++i<=9)
	{
		if (players[i]['fouls'] < 3 || players[i]['out'] < 3 || players[i]['out'] > 3 && players[i]['muted'] !== 1) continue;
		return true;
	}
	return false;
}