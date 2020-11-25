let gameId = -1;
let players = {};
let vars = {}
let prevVars = [];
let prevPlayers = [];
let maxStepBacks = 10;
let MainTimer;
let load = false;
let gameTable = null;
let timerDiv = null;

if (document.readyState == 'loading') {
	document.addEventListener('DOMContentLoaded', startGame);
} else {
	startGame();
}

function startGame() {
	gameTable = document.body.querySelector('table[data-game-id]');
	timerDiv = document.body.querySelector('div.timer');
}
/*
		return [
			'timer' => 6000,
			'stage'  => 'firstNight',
			'prevStage'  =>  '',
			'daysCount'  =>  -1,
			'activeSpeaker'  =>  -1,
			'prevActiveSpeaker'  =>  -1,
			'kill'  =>  [[]],
			'lastWill'  =>  [],
			'daySpeakers'  =>  -1,
			'debaters'  =>  -1,
			'canownerBestMove' => false,
			'ownerBestMove' => -1,
			'currentVote'  => [],
			'bestMove'  =>  [],
			'dopsPoints'  =>  [0=>0.0,1=>0.0,2=>0.0,3=>0.0,4=>0.0,5=>0.0,6=>0.0,7=>0.0,8=>0.0,9=>0.0],
			'winTeam'  =>  0,
			'caption' => 'Фаза ночи.<br>Минута договора игроков мафии.<br>Шериф может взглянуть на город.'
		];
*/

class MafiaGameLogic{
	constructor() {
		
	}
	getNextStage(){
		let shooted = false;
		if ($('.for_kill').length > 0) shooted = this.getShooting();
		if (vars['stage'] === 'firstNight' || vars['stage'] === 'night' && !shooted || vars['stage'] === 'lastWill' && vars['prevStage'] === 'night')
			return 'morning';
		else if (vars['stage'] === 'morning' || (vars['stage'] === 'daySpeaker' && vars['daySpeakers'] > 0))
			return 'daySpeaker';
		else if (vars['stage'] === 'daySpeaker' && vars['daySpeakers'] <= 0)
			return 'court';
		else if ((vars['stage'] === 'court' || vars['stage'] === 'debate') && vars['debaters'] !== -1)
			return 'debate';
		else if ((vars['stage'] === 'court' || vars['stage'] === 'debate' || vars['stage'] === 'lastWill' && vars['prevStage'] !== 'night') && vars.currentVote.length === 0 && vars['lastWill'].length === 0)
			return 'night';
		else if ((((vars['stage'] === 'court' || vars['stage'] === 'debate') || vars['stage'] === 'lastWill')) && vars['lastWill'].length > 0 ||
			vars['stage'] === 'night' && shooted)
			return 'lastWill';
	}
	nextStage(){
		if (MafAct()) return;
		if (!load){
			this.prepeareUndo()
			let nextStage = getNextStage();
			if (vars['stage'] !== nextStage){
				vars['prevStage'] = vars['stage'];
				vars['stage'] = nextStage;
			}
		}
		if (vars['stage'] === 'daySpeaker' || vars['stage'] === 'morning')
			stageDay();
		else if (vars['stage'] === 'court')
			stageCourt();
		else if (vars['stage'] === 'debate')
			stageDebate();
		else if (vars['stage'] === 'lastWill')
			stageLastWill(vars['lastWill'].shift());
		else if (vars['stage'] === 'night')
			stageNight();
		if (!load)
			actionSaveProgress();
	}
	prepeareUndo(){
		if (prevVars.length === maxStepBacks){
			prevVars.shift()
			prevPlayers.shift()
		}
		prevVars.push(JSON.stringify(vars));
		prevPlayers.push(JSON.stringify(players));
		if (prevVars.length === 1){
			//let unDoBotton = document.body.querySelector('div.timer__body__control-button.disabled[data-action-type="time-control"][data-action-mode="undo"]');
			let unDoBotton = timerDiv.querySelector('div.timer__body__control-button.disabled');
			if (unDoBotton !== null)
					unDoBotton.classList.remove('disabled');
		}
	}
	actionPutPlayer(playerRow) {
		if (vars['stage'] === 'finish')
			this.actionAddPoints(playerRow);
		else if (vars['stage'] === 'lastWill' && vars['canownerBestMove'] && vars['bestMove'].length < 3)
			this.actionBestMove(playerRow);
		else if (vars['stage'] === 'daySpeaker' || (vars['stage'] === 'morning' && vars['timer'] < 6000))
			this.actionPutPlayerOnTheVote(playerRow);
		else if (vars['stage'] === 'night')
			this.actionShootPlayer(playerRow);
	}
	actionAddPoints(targetRow) {
		let targetId = targetRow.dataset.playerId;
		let points = prompt('Дополнительные баллы!\r\nНа Ваше усмотрение, сколько можно добавить баллов игроку №'+(targetId+1)+' ('+targetRow.textContent+')?','0.0')
		if (points && points != 0.0)
		{
			points = parseFloat(points);
			alert('Игроку №'+(targetId+1)+(points > 0.0 ? ' добавлено ' : ' назначен штраф в ')+points+' баллов рейтинга');
			vars['dops'][targetId] += points;
			save_progress();
		}
	}
	actionBestMove(targetRow) {
		let targetId = targetRow.dataset.playerId;
		// if (vars['ownerBestMove'] != $('tr.active').attr('id').split('_')[0]){
		if (vars['ownerBestMove'] != vars['activeSpeaker']){
			vars['canownerBestMove'] = false;
			return false;
		}
		e.addClass( 'for_bm' );
		vars['bestMove'].push(targetId+1);
		$('#best_move').removeClass('hide');
		$('#bm').text('Игрока №'+(vars['ownerBestMove']+1)+': '+vars['bestMove'].join(',')+'.');
		if (vars['bestMove'].length === 3){
			if (confirm('Игрок №'+(vars['ownerBestMove']+1)+' назвал, игроками мафии, игроков, под номерами: '+vars['bestMove'].join(',')+'?'))
				vars['ownerBestMove'] = false;
			else{
				vars['bestMove'] = [];
				$('#bm').text('Игрока №'+(vars['ownerBestMove']+1)+': - .');
			}
			$('td.for_bm').removeClass('for_bm');
		}
	}
	actionPutPlayerOnTheVote(targetRow) {
		let targetId = targetRow.dataset.playerId;
		if (players[targetId]['out']>0){
			alert('Не принято!\r\nЗа столом нет такого игрока.');
			return false;
		}
		let act = (vars['timer'] === 6000 ? vars['prevActive'] : vars['active']) || playerId;
		if (act === -1) return false;
		let td = gameTable.querySelector(`tr[data-player-id=${act}] td.puted`);
		if (td.textContent !== '' && playerId !== parseInt(td.textContent)-1) return false;
		++targetId;
		let check = vars.currentVote.indexOf(targetId);
		if (check === -1){
			td.textContent = playerId;
			targetRow.classList.add( "putted-on-vote" );
			vars.currentVote.push(i);
			players[act]['puted'][vars['day_count']] = i;
			save_log('Игрок №'+(act+1)+' выставил игрока №'+i+' на голосование!');
		}
		else{
			if (players[act]['puted'][vars['day_count']] === i){
				td.removeClass( "for_vote" ).text('');
				e.removeClass( "for_vote" );
				vars.currentVote.splice(check,1);
				players[act]['puted'][vars['day_count']] = -1;
				save_log('Ошибочное выставление. Отмена!');
			}
			else{
				alert('Не принято!\r\nУже выстален.');
				save_log('Игрок №'+(act+1)+' попытался выставить игрока №'+i+' на голосование.BRНе принято - уже выставлен!');
				return false;
			}
		}
		if (vars.currentVote.length>0)
			this.showCourtRoom();
		else this.hideCourtRoom();
	}
	actionShootPlayer(targetRow) {
		let targetId = targetRow.dataset.playerId;
		if ($('tr.active').length > 0)
			return false;
		vars['kill'][vars['daysCount']].push(targetId);
		$('tr[id^="'+targetId+'_"]').addClass('for_kill');
	}
}

let game = new MafiaGameLogic();

actionHandler.gamePutPlayer = function ({ target, event }) {
	let playerRow = target.closest('tr');
    game.actionPutPlayer(playerRow);
};

// function getGameData() {
// 	gameId = document.body.querySelector('table.content__game__table').dataset.gameId;
// 	postAjax({
// 		data: {
// 			need: "game-data",
// 			gid: gameId
// 		},
// 		successFunc: function (result) {
// 			result = JSON.parse(result);
// 			if (result["error"] === 0) {
// 				let data = JSON.parse(result["txt"]);
// 				players = JSON.parse(data['players']);
// 				vars = JSON.parse(data['vars']);
// 				console.log(players);
// 				console.log(vars);
// 			} else alert(result["txt"]);
// 		},
// 	});
// 	// let script = document.createElement('script');
// 	// script.src = window.location.protocol +'//'+ window.location.hostname + "/switcher.php/?need=script&php=game-data&gid=" + gameId;
// 	// document.head.append(script);
// 	// script.onload = function (event) {
// 	// 	console.log()
// 	// };
// }