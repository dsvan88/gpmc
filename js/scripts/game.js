var dblclick_func = null;
var id_game = <?= isset($_GET['g_id']) && $_GET['g_id'] > 0 ? $_GET['g_id'] : -1 ?>;
if (document.readyState == 'loading') {
	// ещё загружается, ждём события
	document.addEventListener('DOMContentLoaded', getGameData);
} else {
	// DOM готов!
	getGameData();
}
/* 

$(function(){
	$('#MainBody').off('click','#StopGame');
	$('#MainBody').on('click','#StopGame', function(){
		cancel_game();
	})
	$('#MainBody').off('click','#SaveEnd');
	$('#MainBody').on('click','#SaveEnd', function(){
		save_progress();
	})
	//--------------------------------------------------- Блок таймера
	$('#MainBody').off('click', 'td#TimerStart');
	$('#MainBody').on('click', 'td#TimerStart', function(){
		if ($(this).hasClass('disabled')) return false;
		if ($('td#TimerStart > img').prop('title') !== 'Пауза')
			timer_start()
		else
			timer_pause()
		});
	$('#MainBody').off('click', 'td#TimerReset');
	$('#MainBody').on('click', 'td#TimerReset', function(){
		if ($(this).hasClass('disabled')) return false;
		timer_reset();
	});
	$('#MainBody').off('click', 'td#TimerNext');
	$('#MainBody').on('click', 'td#TimerNext', function(){
		if ($(this).hasClass('disabled')) return false;
		timer_reset(1);
	});
	//--------------------------------------------------- Блок отмены последних действий
	$('#MainBody').off('click', 'td#Undo');
	$('#MainBody').on('click', 'td#Undo', function(){
		if (!$(this).hasClass('disabled'))
		{
			load = true;
			vars = JSON.parse(prev_vars.pop());
			players = JSON.parse(prev_players.pop());
			load_state();
		}
	});
	//--------------------------------------------------- Блок кликаний по td
	$("table.GameTable").off('click')
	$("table.GameTable").on('click', 'td', function(){
		dblclick_func = setTimeout(function() { if (dblclick_func === null)	{ } else { clearTimeout(dblclick_func);  dblclick_func = false; };}, 200)
	});
	$("table.GameTable").off('dblclick')
	$("table.GameTable").on('dblclick', 'td', function(){
		clearTimeout(dblclick_func);
		if ($(this).hasClass('vote_num') || $(this).hasClass('player_name'))
			Do_PutHim($(this));
		else if ($(this).hasClass('foul'))
			Do_Foul($(this))
	});
	//--------------------------------------------------- Блок касаний td
	$("#MainBody").off('touchstart');
	$("#MainBody").on('touchstart', 'td.vote_num,td.player_name,td.foul', function(e){
		if(!dblclick_func){
			dblclick_func=setTimeout(function(){
				dblclick_func=null;
			},200);
		} else {
			clearTimeout(dblclick_func);
			dblclick_func=null;
			if ($(this).hasClass('vote_num') || $(this).hasClass('player_name'))
				Do_PutHim($(this));
			else if ($(this).hasClass('foul'))
				Do_Foul($(this));
		}
		e.preventDefault()
	});
}); */
function getGameData() {
	let script = document.createElement('script');
	let gameId = document.body.querySelector('table.content__game__table').dataset.gameId;
	script.src = window.location.protocol +'//'+ window.location.hostname + "/switcher.php/?need=script&php=game-data&gid=" + gameId;
	document.head.append(script);
	script.onload = function () {
		
	};
}