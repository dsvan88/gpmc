<?
session_start();
header('Content-Type: text/javascript');
?>
function change_active_speaker(id=-1)
{
	if (load) return false;
	$('tr.active').removeClass('active');
	if (id !== -1) $('tr[id^="'+id+'_"]').addClass('active');
}
function show_courtroom(){$('#OnVote').text("На голосование выставлены игроки под номерами: "+vars.currentVote.join(', ')+'.').removeClass( "hide" )}
function hide_courtroom(){$('#OnVote').text('').addClass( "hide" )}
function NextSpeaker(start)
{
	let i = start-1;
	for(;;)
	{
		if (++i > 9) i = 0;
		if (i === vars['active']) continue;
		else if (players[i]['out'] > 2 && players[i]['muted'] === 1)
		{
			--vars['day_speaker'];
			Do_UnmuteHim(id);
			continue;
		}
		else if (players[i]['out'] > 0) continue;
		else
		{
			--vars['day_speaker'];
			if (players[i]['muted'] !== 1) break;
			else 
			{
				if (GetActivePlayers() < 5) 
				{
					vars['timer'] = 3000;
					Do_UnmuteHim(i);
					break;
				}
				let put = parseInt(prompt('Игрок №'+(i+1)+' молчит, но может выставить кандидатуру: ','0'));
				if (put > 0) PutHim($('tr[id^="'+(put-1)+'_"] td.vote_num'),i);
				continue;
			}
		}	
	}
	return i;
}
function Do_UnmuteHim(id)
{
	$('tr[id^="'+id+'_"] > td#foul_3 > img').css({'display':'none'});
	if ($('tr[id^="'+id+'_"] td.prim').text() === 'Молчит') $('tr[id^="'+id+'_"] td.prim').text('');
	players[id]['muted'] = 0;
}
function GetActivePlayers(r=0)
{
	let pc = 0, i = -1
	while(++i<=9)
	{
		if (players[i]['out']>0) continue;
		if (r===2 && (players[i]['role'] === 0 || players[i]['role'] === 4)) continue; // Если ищем мафов - отсекаем миров
		if (r===1 && (players[i]['role'] === 1 || players[i]['role'] === 2)) continue; // Если ищем миров - отсекаем мафов
		++pc;
	}
	return pc;
}
function set_PhaseState(s)
{
	if (!load)
	{
		prev_text.push(s);
		save_log(s);
	}
	$('span.TimerHeader').html(s.replace(/BR/g,'<br>'));
}
<? if (isset($_SESSION['status']) && $_SESSION['status'] > 0):?>
function timer_start()
{
	$('td#TimerStart > img').prop({'src':'../css/images/pause.png',
		'title' : 'Пауза',
		'alt' : 'Пауза'
	});
	MainTimer = setInterval(function() { 
		if (vars['timer'] > 0)
		{
			$('th span.stopwatch').text(inttotime(vars['timer'] -= 5));
			if ([1000,500,300,200,100].indexOf(vars['timer']) !== -1) beep();
		}
		else
		{
			beep();
			timer_reset(1);
		}
	}, 50);
}
function timer_pause()
{
	clearInterval(MainTimer);
	$('td#TimerStart > img').prop({'src':'../css/images/start.png',
		'title' : 'Старт',
		'alt' : 'Старт'
	});
}
function timer_reset(n=0)
{
	timer_pause();
	vars['timer'] = 6000;
	$('th span.stopwatch').text(inttotime(vars['timer']));
	if(n !== 0) next();
}
function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
}
<?endif;?>
function inttotime(t)
{
	m = Math.floor(t/6000);
	s = Math.floor(t%6000/100);
	ms = t%100;
	return '0'+m+':'+(s > 9 ? s : '0'+s)+':'+(ms > 9 ? ms : '0'+ms);
}
function save_log(s)
{
	<? if (isset($_SESSION['status']) && $_SESSION['status'] > 0):?>
	$.ajax({
		url:'switcher.php'
		, type:'POST'
		, data:'need=save_log&i='+id_game+'&t=1'+'&log='+s+'HR'
	});
	<?endif;?>
	$('#Log_'+id_game).html($('#Log_'+id_game).html()+s.replace(/BR/g,'<br>')+'<hr>');
}
function save_progress(){
	$.ajax({
		url:'switcher.php'
		, type:'POST'
		, data:'need=save_game&w='+vars['win']+'&p='+JSON.stringify(players)+'&v='+JSON.stringify(vars)+'&text='+prev_text[prev_text.length-1]+'&i='+id_game+'&t=0'
		, success: function(res){
			if (res === '') return false;
			players = JSON.parse(res);
			let i = -1;
			while(++i<=9)
				$('tr#'+i+'_'+players[i]['id']+' td.player_name > span.points').html(players[i]['points']+'&nbsp;').addClass((players[i]['points'] > 0.0 ? 'positive' : 'negative'));
		}
	});
}
function load_state()
{
	Do_ClearCourtRoom(0)
	//------------------------------------------ Обнуление состояний игроков
	$('tr').removeClass('active out');
	$('td').removeClass('fail for_vote for_kill for_bm');
	$('td.prim').text('')
	$('td#foul_3 > img').css({'display':'none'})
	//------------------------------------------ Применение загруженных состояний игроков
	let i, check = vars.currentVote.length;
	if (check > 0)
	{
		show_courtroom();
		i = -1;
		while(++i<check)
		{
			$('tr[id^="'+(vars.currentVote[i]-1)+'_"] td.vote_num').addClass('for_vote');
			let i2 = -1;
			while(++i2<=9) if (players[i2]['puted'][vars['day_count']] == vars.currentVote[i]) break;
			$('tr[id^="'+i2+'_"] td.puted').addClass('for_vote').text(vars.currentVote[i]);
		}
	}
	$('tr[id^="'+vars['active']+'_"]').addClass('active');
	if (prev_text.length > 0)
		set_PhaseState(prev_text.pop());
	i = -1;
	while(++i<=9)
	{
		let tr = 'tr[id^="'+i+'_"]';
		for(x=1;x<=4;x++)
			if(players[i]['foul'] >= x)
			{
				$(tr+' td.foul#foul_'+x).addClass('fail');
				if (players[i]['foul']===3 && players[i]['muted'] === 1)
				{
					$('tr[id^="'+i+'_"] > td#foul_3 > img').css({'display':'block'});
					$('tr[id^="'+i+'_"] td.prim').text('Молчит');
				}
			}
			else break;
		if (players[i]['out'] > 0)
		{
			$(tr).addClass('out');
			$(tr+' td.prim').text(reasons[players[i]['out']]);
		}
	}
	if (prev_vars.length === 0)
		$('td#Undo').addClass('disabled');
	save_log('Загрузка состояния выполнена!');
	if (!load) next();
	else load = false;
}
function cancel_game()
{
	if ((w = parseInt(prompt('Вы уверены, что пора прекратить игру?\r\nВведите победителя: 1 (Мирные), 2 (Мафия), 3 (Ничья), 0 - отмена','0'))) > 0)
	{
		save_log('Игра прекращена ведущим!');
		MafAct(w);
	}
}