
class MafiaGameLogic{
	prevVars = [];
	prevPlayers = [];
	prevText = '';
	maxStepBacks = 10;
	MainTimer;
	load = false;
	gameTable = null;
	timerDiv = null;
	gameId = -1;
	players = {};
	vars = {};
	playersShooted = [];
	gameTable = null;
	timerDiv = null;
	constructor() {
		this.gameTable = document.body.querySelector('table[data-game-id]');
		this.gameId = this.gameTable.dataset.gameId;
		this.timerDiv = document.body.querySelector('div.timer');
		this.loadGameData();
	}
	checkNextStage(){
		let shooted = false;
		if (this.playersShooted.length > 0) shooted = this.getShooting();
		if (this.vars['stage'] === 'firstNight' || this.vars['stage'] === 'night' && !shooted || this.vars['stage'] === 'lastWill' && this.vars['prevStage'] === 'night')
			return 'morning';
		else if (this.vars['stage'] === 'morning' || (this.vars['stage'] === 'daySpeaker' && this.vars['daySpeakers'] > 0))
			return 'daySpeaker';
		else if (this.vars['stage'] === 'daySpeaker' && this.vars['daySpeakers'] <= 0)
			return 'court';
		// else if ((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate') && this.vars['debaters'] != -1)
		else if ((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate') && this.vars['currentVote'].length > 0)
			return 'debate';
		else if ((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate' || this.vars['stage'] === 'lastWill' && this.vars['prevStage'] !== 'night') && this.vars.currentVote.length === 0 && this.vars['lastWill'].length === 0)
			return 'night';
		else if ((((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate') || this.vars['stage'] === 'lastWill')) && this.vars['lastWill'].length > 0 ||
			this.vars['stage'] === 'night' && shooted)
			return 'lastWill';
	}
	nextStage() {
		// console.log(this.vars);
		// console.log(this.players);
		if (this.checkWinner()) return false;
		if (!load){
			this.prepeareUndo()
			let nextStage = this.checkNextStage();
			if (this.vars['stage'] !== nextStage){
				this.vars['prevStage'] = this.vars['stage'];
				this.vars['stage'] = nextStage;
			}
		}
		if (this.vars['stage'] === 'daySpeaker' || this.vars['stage'] === 'morning')
			this.stageDay();
		else if (this.vars['stage'] === 'court')
			this.stageCourt();
		else if (this.vars['stage'] === 'debate')
			this.stageDebate();
		else if (this.vars['stage'] === 'lastWill')
			this.stageLastWill(this.vars['lastWill'].shift());
		else if (this.vars['stage'] === 'night')
			this.stageNight();
		// if (!load)
		// 	this.actionSaveProgress();
	}
	stageDay() {
		if (this.vars['stage'] === 'morning')
		{
			this.vars['kill'][++this.vars['daysCount']]= [];
			let i = -1;
			while(++i<=9)
				this.players[i]['puted'][this.vars['daysCount']] = -1;
			this.vars['daySpeakers'] = this.playerGetActiveCount();
			this.vars['active'] = this.playerGetNextSpeaker(this.vars['daysCount']);
			const killed = this.gameTable.querySelectorAll(`tr.for-kill`);
			if (killed.length !== 1)
				killed.forEach(playerRow => playerRow.classList.remove('for-kill'));

		}
		else 
		{
			this.vars['prev_active'] = this.vars['active'];
			this.vars['active'] = this.playerGetNextSpeaker(this.vars['active']+1);
		}
		this.activeSpeakerChange(this.vars['active']);
		// set_PhaseState('Фаза дня.BRДень №'+this.vars['daysCount']+'BRРечь игрока №'+(this.vars['active']+1));
	}
	prepeareUndo(){
		if (this.prevVars.length === this.maxStepBacks){
			this.prevVars.shift()
			this.prevPlayers.shift()
		}
		this.prevVars.push(JSON.stringify(this.vars));
		this.prevPlayers.push(JSON.stringify(this.players));
		if (this.prevVars.length === 1){
			let unDoBotton = this.timerDiv.querySelector('div.timer__body__control-button.disabled');
			if (unDoBotton !== null)
					unDoBotton.classList.remove('disabled');
		}
	}
	actionPutPlayer(event) {
		const playerRow = event.target.closest('tr');
		if (this.vars['stage'] === 'finish')
			this.actionAddPoints(playerRow);
		else if (this.vars['stage'] === 'lastWill' && this.vars['ownerCanSetBestMove'] && this.vars['bestMove'].length < 3)
			this.actionBestMove(playerRow);
		else if (this.vars['stage'] === 'daySpeaker' || (this.vars['stage'] === 'morning' && this.vars['timer'] < 6000))
			this.actionPutPlayerOnTheVote(playerRow);
		else if (this.vars['stage'] === 'night')
			this.actionShootedPlayer(playerRow);
	}
	actionAddPoints(targetRow) {
		const targetId = targetRow.dataset.playerId;
		const points = prompt(`Дополнительные баллы!
На Ваше усмотрение, сколько можно добавить баллов игроку №${targetId+1} (${targetRow.textContent})?`,'0.0')
		if (points && points != 0.0)
		{
			points = parseFloat(points);
			alert('Игроку №'+(targetId+1)+(points > 0.0 ? ' добавлено ' : ' назначен штраф в ')+points+' баллов рейтинга');
			this.vars['dops'][targetId] += points;
			this.stateSave();
		}
	}
	actionBestMove(targetRow) {
		const targetId = targetRow.dataset.playerId;
		if (this.vars['ownerBestMove'] != this.vars['activeSpeaker']){
			this.vars['ownerCanSetBestMove'] = false;
			return false;
		}
		targetRow.classList.add( 'for-best-move' );
		this.vars['bestMove'].push(targetId + 1);
		document.body.querySelector('.best-move').classList.remove('hidden');
		const bestMoveText = document.body.querySelector('.best-move__text');
		bestMoveText.textContent = `Игрока №${this.vars['ownerBestMove']+1}: ${this.vars['bestMove'].join(',')}.`;
		if (this.vars['bestMove'].length === 3){
			if (confirm(`Игрок №${this.vars['ownerBestMove']+1} назвал, игроками мафии, игроков, под номерами: ${this.vars['bestMove'].join(',')}?`))
				this.vars['ownerBestMove'] = false;
			else{
				this.vars['bestMove'] = [];
				bestMoveText.textContent = `Игрока №${this.vars['ownerBestMove']+1}: - .`;
			}
			this.gameTable.querySelectorAll('td.for-best-move').forEach(td => td.classList.remove('for-best-move'));
		}
	}
	actionPutPlayerOnTheVote(targetRow,playerVotedId=-1) {
		const targetId = targetRow.dataset.playerId;
		if (this.players[targetId]['out']>0){
			alert('Не принято!\r\nЗа столом нет такого игрока.');
			return false;
		}
		let act = playerVotedId;
		if (act === -1) {
			if (this.vars['timer'] === 6000)
				act = this.vars['prevActive'];
			else
				act = this.vars['active'];
		}
		if (act === -1) return false;
		const td = this.gameTable.querySelector(`tr[data-player-id="${act}"] td.puted`);
		if (td.textContent !== '' && targetId !== parseInt(td.textContent)-1) return false;
		++targetId;
		const check = this.vars.currentVote.indexOf(targetId);
		if (check === -1){
			td.textContent = targetId;
			targetRow.classList.add( "for-vote" );
			this.vars.currentVote.push(targetId);
			this.players[act]['puted'][this.vars['daysCount']] = targetId;
			// save_log('Игрок №'+(act+1)+' выставил игрока №'+targetId+' на голосование!');
		}
		else{
			if (this.players[act]['puted'][this.vars['daysCount']] === targetId) {
				td.textContent = '';
				targetRow.classList.remove( "for-vote" );
				this.vars.currentVote.splice(check,1);
				this.players[act]['puted'][this.vars['daysCount']] = -1;
				// save_log('Ошибочное выставление. Отмена!');
			}
			else{
				alert('Не принято!\r\nУже выстален.');
				// save_log('Игрок №'+(act+1)+' попытался выставить игрока №'+i+' на голосование.BRНе принято - уже выставлен!');
				return false;
			}
		}
		if (this.vars.currentVote.length>0)
			this.showCourtRoom();
		else this.hideCourtRoom();
	}
	actionShootedPlayer(targetRow) {
		const targetId = targetRow.dataset.playerId;
		if (this.gameTable.querySelector('tr.active-speaker') !== null)
			return false;
		this.vars['kill'][this.vars['daysCount']].push(targetId);
		targetRow.classList.add('for-kill');
	}
	stateSave() {
		this.prepeareUndo();
		const formData = new FormData();
		formData.append("need", "do_game-save-data");
		formData.append("gid", this.gameId);
		formData.append("players", JSON.stringify(this.players));
		formData.append("vars", JSON.stringify(this.vars));
		formData.append("win", this.vars['win']);
		formData.append("text", '');
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] === 0) {
					console.log(result);
				} else alert(result["text"]);
				/* if (res === '') return false;
				players = JSON.parse(res);
				let i = -1;
				while(++i<=9)
					$('tr#'+i+'_'+players[i]['id']+' td.player_name > span.points').html(players[i]['points']+'&nbsp;').addClass((players[i]['points'] > 0.0 ? 'positive' : 'negative')); */
			},
		});

		// $.ajax({
		// 	url:'switcher.php'
		// 	, type:'POST'
		// 	, data:'need=save_game&w='+vars['win']+'&p='+JSON.stringify(players)+'&v='+JSON.stringify(vars)+'&text='+prev_text[prev_text.length-1]+'&i='+id_game+'&t=0'
		// 	, success: function(res){
				
		// 	}
		// });
	}
	stateLoad()
	{
		this.courtRoomClear(0)
		//------------------------------------------ Обнуление состояний игроков
		document.body.querySelectorAll('tr').forEach(tableRow => {
			tableRow.classList.remove('active');
			tableRow.classList.remove('out');
			tableRow.querySelectorAll('td').forEach(tableCell => {
				tableCell.classList.remove('fail');
				tableCell.classList.remove('for-vote');
				tableCell.classList.remove('for-kill');
				tableCell.classList.remove('for-best-move');
				if (tableCell.classList.contains('prim')){
					tableCell.textContent = '';
				}
				if (tableCell.dataset.foulId == "2"){
					tableRow.querySelector('i.fa-microphone-slash').classList.add("hidden");
				}
			})
		})
		//------------------------------------------ Применение загруженных состояний игроков
		let i, check = this.vars.currentVote.length;
		if (check > 0)
		{
			this.courtRoomShow();
			i = -1;
			while(++i<check)
			{
				this.gameTable.querySelector(`tr[data-player-id="${act}"] td.vote-num`).forEach(td => td.classList.remove('for-best-move'));
				this.gameTable.querySelector(`tr[data-player-id="${vars.currentVote[i]-1}"]`).classList.add('for-vote');
				let x = -1;
				while (++x <= 9)
					if (this.players[x]['puted'][this.vars['daysCount']] == this.vars.currentVote[i]) break;
				this.gameTable.querySelector(`tr[data-player-id="${x}"] td.vote-num`).classList.add('for-vote');
				this.gameTable.querySelector(`tr[data-player-id="${x}"] td.vote-num`).textContent = this.vars.currentVote[i];
			}
		}
		this.gameTable.querySelector(`tr[data-player-id="${this.vars['active']}"]`).classList.add('active');
		// if (this.prevText.length > 0)
		// 	set_PhaseState(this.prevText.pop());
		index = -1;
		while(++index<=9)
		{
			let tableRow = this.gameTable.querySelector(`tr[data-player-id="${index}"]`);
			for(x=1;x<=4;x++)
				if(players[index]['foul'] >= x)
				{
					let foulCell = tableRow.querySelector(`td[data-foul-id="${x}"]`);
					foulCell.classList.add('fail');
					if (players[i]['foul']===3 && players[i]['muted'] === 1)
					{
						tableRow.querySelector(`td[data-foul-id="${x}"] i.fa-microphone-slash`).classList.add('hidden');
						tableRow.querySelector(`td.prim`).textContent = 'Молчит';
					}
				}
				else break;
			if (players[i]['out'] > 0)
			{
				tableRow.classList.add('out');
				tableRow.querySelector('td.prim').textContent = this.reasons[players[index]['out']];
			}
		}
		if (this.prevVars.length === 0)
			document.body.querySelector('[data-timer-action="undo"]').classList.add('disabled');
		// save_log('Загрузка состояния выполнена!');
/* 		if (!load) next();
		else load = false; */
	}
	gameEnd()	{
		if ((w = parseInt(prompt('Вы уверены, что пора прекратить игру?\r\nВведите победителя: 1 (Мирные), 2 (Мафия), 3 (Ничья), 0 - отмена','0'))) > 0)
		{
			save_log('Игра прекращена ведущим!');
			this.checkWinner(w);
		}
	}
	loadGameData() {
		let _self = this;
		postAjax({
			data: `{"need": "get_game-load-data","gid": "${this.gameId}"}`,
			successFunc: function (result) {
				if (result["error"] === 0) {
					_self.players = JSON.parse(result['players']);
					_self.vars = JSON.parse(result['vars']);
					eveningId = result['eid'];
				} else alert(result["text"]);
			},
		});
	}
	activeSpeakerChange(id=-1)
	{
		// if (load) return false;
		document.body.querySelectorAll('tr.active').forEach(td => td.classList.remove('active'));
		if (id !== -1)
			document.body.querySelector(`tr[data-player-id="${id}"]`).classList.add('active');
	}
	playersFoulsCheck(){
		let i = -1;
		while(++i<=9)
		{
			if (this.players[i]['fouls'] < 3 || this.players[i]['out'] < 3 || this.players[i]['out'] > 3 && this.players[i]['muted'] !== 1) continue;
			return true;
		}
		return false;
	}
	courtRoomClear(next=1){
		if (!load)
			this.vars.currentVote.length=0;
		this.courtRoomHide();
		document.body.querySelectorAll('td.for-vote,td.puted').forEach(td => {
			if (td.classList.contains('for-vote'))
				td.classList.remove('for-vote')
			if (td.classList.contains('puted'))
				td.textContent = '';
		});
		this.activeSpeakerChange();
		if (next === 1) this.nextStage();
	}
	courtRoomHide() {
		return true;
	}
	courtRoomShow() {
		const courtRoom = document.body.querySelector('.players-on-vote')
		courtRoom.innerHTML = `На голосование выставлены игроки под номерами: ${this.vars.currentVote.join(', ')}.`
		courtRoom.classList.remove("hidden");
	}
	courtRoomAction(debatesMode=0){
		this.activeSpeakerChange();
		if (this.playersFoulsCheck())
		{
			this.courtRoomClear(1);
			return true;
		}
		// set_PhaseState('Зал суда.BRПросьба убрать руки от стола, прекратить жестикуляцию и агитацию.BRНа '+(d===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+vars.currentVote.join(', '));
		alert('Уважаемые игроки, переходим в зал суда!\r\nНа '+(debatesMode===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+this.vars.currentVote.join(', '));
		let i=-1,vote_available=0,players_count=0,voted=[],cv=[],prev_max=0,debate=[], string='';
		if (this.vars.currentVote.length === 0)
		{
			alert('На голосование никто не выставлен. Голосование не проводится.');
			this.courtRoomClear(1);
			return true;
		}
		else if (this.vars.currentVote.length === 1)
		{
			string = 'На голосование был выставлен лишь 1 игрок\r\n';
			if (this.vars['daysCount'] > 0)
			{
				alert(`${string} Наш город покидает игрок № ${this.vars.currentVote[0]}!\r\nУ вас есть 1 минута для последней речи`);
				this.playerOut(this.vars.currentVote[0]-1,2);
			}
			else
				alert(string+'Этого недостаточно для проведения голосования. Наступает фаза ночи!')
			this.courtRoomClear(1);
			return true;
		}
		votes_available = players_count = this.playerGetActiveCount();
		i=-1;
		while(++i<this.vars.currentVote.length)
		{
			cv.push(this.vars.currentVote[i]);
			if (votes_available < 1) 
			{
				voted.push(0)
				string+=`Игрок  №${this.vars.currentVote[i]}}\tГолоса: 0BR`;
				continue;
			}
			vote= i < this.vars.currentVote.length-1 ? parseInt(prompt(this.vars.currentVote[i]+'! Кто за то, что бы наш город покинул игрок под № '+this.vars.currentVote[i],'0')) : vote_available;
			string+='Игрок  № '+vars.currentVote[i]+"\tГолоса: "+vote+"BR";
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
		string = 'Голоса распределились следующим образом:BR'+string;
		if (debate.length===0)
		{
			string+='Нас покидает Игрок под № '+vars.currentVote[prev_max]+'.BRУ вас прощальная минута.';
			playerOut(vars.currentVote[prev_max]-1,2);
		}
		else
			string+='В нашем городе перестрелка. Между игроками под номерами: '+debate.join(', ');
		alert(string.replace(/BR/g, '\r\n'));
		this.gameTable.querySelectorAll('tr.for-vote').forEach(item => item.classList.remove('for-vote'));

		if (debatesMode > 0 && debate.length === this.vars.currentVote.length)
		{
			if (players_count > 4)
			{
				vote= parseInt(prompt('Кто за то, что все игроки под номерами: '+debate.join(', ')+' покинули стол?','0'));
				if ( vote > players_count/2)
				{
					string=`Большинство (${vote} из ${players_count}) - за!BRИгроки под номерами: ${debate.join(', ')} покидают стол.`;
					i=-1;
					while(++i<debate.length)
						playerOut(debate[i]-1,2);
				}
				else 
					string=`Большинство (${vote} из ${players_count}) - - против!BRНикто не покидает стол.`;
			}
			else 
				string='При количестве игроков менее 5 нельзя поднять 2 и более игроков.BRНикто не покидает стол.';
			alert(string.replace(/BR/g,'\r\n'));
			debate.length=0;
		}
		if (debate.length > 0)
		{
			this.vars.currentVote=debate;
			courtRoomDebate();
		}
		else
			courtRoomClear(1)
	}
	courtRoomDebate(){
		this.activeSpeakerChange(this.vars.currentVote[++this.vars['debater']]-1);
		// set_PhaseState('Фаза перестрелки.BRРечь игрока №'+(vars.currentVote[vars['debater']]));
		let _self = this;
		let check = setInterval(function() { 
			if (_self.vars['debater'] !== -1)
			{
				if (_self.vars['timer'] === 6000) 
				{
					_self.vars['timer'] = 3000;
					document.body.querySelector('div.timer__watchclock').textContent = inttotime(_self.vars['timer']);
				}
			}
			else
			{
				clearInterval(check);
				courtRoomAction(1);
			}
		}, 50);
	}
	playerOut(id, reason) {
		let tableRow = this.gameTable.querySelector(`tr[data-player-id="${id}"]`);
		tableRow.classList.add('out');
		tableRow.querySelector(`td.prim`).textContent = reasons[reason];
		players[id]['out'] = reason;
		if (reason < 3) vars['last_will'].push(id);
		else players[id]['muted'] = 1;
		// save_log('Игрок №'+(id+1)+' покидает наш город. Причина: '+reasons[reason]+'!');
		return vars['last_will'];
	}
	playerGetActiveCount(role=0)
	{
		let playersCount = 0, index = -1;
		while(++index < 9)
		{
			if (this.players[index]['out']>0) continue;
			if (role===2 && (this.players[index]['role'] === 0 || this.players[index]['role'] === 4)) continue; // Если ищем мафов - отсекаем миров
			if (role===1 && (this.players[index]['role'] === 1 || this.players[index]['role'] === 2)) continue; // Если ищем миров - отсекаем мафов
			++playersCount;
		}
		return playersCount;
	}
	gameSetStatePhrase(string)
	{
		if (!load)
		{
			this.prevText.push(string);
			// save_log(s);
		}
		// $('span.TimerHeader').html(s.replace(/BR/g,'<br>'));
	}
	checkWinner(winner = 0){
		let maf=0,index=-1,alive=0,string='';
		while(++index<=9)
		{
			if (this.players[index]['out']>0) continue;
			++alive;
			if (this.players[index]['role']==1 || this.players[index]['role']==2) ++maf;
		}
		if (maf===0 || winner === 1)
		{
			this.vars['win'] = 1;
			string = 'Мирный город!BRТеперь, Ваши дети могут спать спокойно';
		}
		else if (maf>=alive-maf || winner === 2)
		{
			this.vars['win'] = 2;
			string = "Мафию!BRТеперь, Ваши дети могут спать сыто и спокойно!";
		}
		if (string!=='')
		{
			// timer_reset();
			index = -1;
			const roles = ['', 'mafia', 'don', 'sheriff'];
			while(++index<=9)
			{
				if (this.players[index]['role'] === 0) continue;
				this.gameTable.querySelector(`tr[data-player-id="${index}"]`).classList.add(roles[this.players[index]['id']]);
			}
			// $(".for_image,.events").removeClass("hide");
			gameSetStatePhrase('Поздравляем с победой: '+string);
			alert("Поздравляем с победой: "+string.replace(/BR/g,'\r\n'));
			// $('#timer td').addClass('disabled');
			this.vars['stage'] = 'finish';
			// save_progress();
			return true;
		}
		return false;
	}
	playerGetNextSpeaker(start)
	{
		console.log(start);
		let index = start-1;
		for(;;)
		{
			console.log(index);
			console.log(this.players[index]);
			if (++index > 9) index = 0;
			if (index === this.vars['active']) continue;
			else if (this.players[index]['out'] > 2 && this.players[index]['muted'] === 1)
			{
				--this.vars['daySpeakers'];
				this.playerUnmute(index);
				continue;
			}
			else if (this.players[index]['out'] > 0) continue;
			else
			{
				--this.vars['daySpeakers'];
				if (this.players[index]['muted'] !== 1) break;
				else 
				{
					if (this.playerGetActiveCount() < 5) 
					{
						this.vars['timer'] = 3000;
						this.playerUnmute(index);
						break;
					}
					let put = parseInt(prompt('Игрок №'+(index+1)+' молчит, но может выставить кандидатуру: ','0'));
					if (put > 0)
						this.actionPutPlayerOnTheVote(document.body.querySelector(`tr[data-player-id="${(put - 1)}"]`), index);
					continue;
				}
			}	
		}
		return index;
	}
	playerUnmute(id)
	{
		const tableRow = this.gameTable.querySelector(`tr[data-player-id="${id}"]`);
		tableRow.querySelector(`td[data-foul-id=3] > i.fa-microphone-slash`).style.display='none';
		const prim = tableRow.querySelector(`td.prim`);
		if (prim.textContent === 'Молчит')
			prim.textContent='';
		this.players[id]['muted'] = 0;
	}
}