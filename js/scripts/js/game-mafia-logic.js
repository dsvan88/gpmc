
class MafiaGameLogic extends MafiaMainFuncs {
	constructor(...args) {
		super(...args)
		this.gameTable = document.body.querySelector('table[data-game-id]');
		this.gameId = this.gameTable.dataset.gameId;
		this.timerDiv = document.body.querySelector('div.timer');
		this.HeaderTextBlock = document.body.querySelector('div.game__stage-text');
		this.gameLogBlock = document.body.querySelector('.game__log-text');
		this.loadGameData();
	}
	checkNextStage(){
		let shooted = false;
		if (this.gameTable.querySelectorAll('tr.for-kill').length > 0) {
			shooted = this.playerGetShooted();
		}
		if (this.vars['stage'] === 'firstNight' || this.vars['stage'] === 'night' && !shooted || this.vars['stage'] === 'lastWill' && this.vars['prevStage'] === 'night')
			return 'morning';
		else if (this.vars['stage'] === 'morning' || (this.vars['stage'] === 'daySpeaker' && this.vars['daySpeakers'].length > 0))
			return 'daySpeaker';
		else if (this.vars['stage'] === 'daySpeaker' && this.vars['daySpeakers'].length <= 0)
			return 'court';
		else if ((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate') && this.vars['currentVote'].length > 0)
			return 'debate';
		else if ((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate' || this.vars['stage'] === 'lastWill' && this.vars['prevStage'] !== 'night') && this.vars.currentVote.length === 0 && this.vars['lastWill'].length === 0)
			return 'night';
		else if ((((this.vars['stage'] === 'court' || this.vars['stage'] === 'debate') || this.vars['stage'] === 'lastWill')) && this.vars['lastWill'].length > 0 ||
			this.vars['stage'] === 'night' && shooted)
			return 'lastWill';
	}
	nextStage() {
		if (this.checkWinner()) return false;
		if (!load){
			this.gamePrepeareUndo()
			let nextStage = this.checkNextStage();
			if (this.vars['stage'] !== nextStage){
				this.vars['prevStage'] = this.vars['stage'];
				this.vars['stage'] = nextStage;
			}
		}
		if (this.vars['stage'] === 'daySpeaker' || this.vars['stage'] === 'morning') {
			this.stageDay();
		}
		else if (this.vars['stage'] === 'court') {
			if (!this.stageCourt())
				this.courtRoomClear(1);
			else
				this.courtRoomAction(0);
		}
		else if (this.vars['stage'] === 'debate')
			this.courtRoomDebate(1);
		else if (this.vars['stage'] === 'lastWill') {
			this.stageLastWill(this.vars['lastWill'].shift());
		}
		else if (this.vars['stage'] === 'night')
			this.stageNight();
	}
	stageDay() {
		if (this.vars['stage'] === 'morning')
		{
			this.vars['kill'][++this.vars['daysCount']]= [];
			let i = -1;
			while(++i<=9)
				this.players[i]['puted'][this.vars['daysCount']] = -1;
			this.vars['daySpeakers'] = this.playersGetSpeakersArray();
			this.vars['activeSpeaker'] = this.playerGetNextSpeaker();
		}
		else 
		{
			this.vars['prevActiveSpeaker'] = this.vars['activeSpeaker'];
			this.vars['activeSpeaker'] = this.playerGetNextSpeaker();
		}
		console.log(this.vars['activeSpeaker']);
		this.activeSpeakerChange(this.vars['activeSpeaker']);
		this.gameSetStatePhrase(`Фаза дня.BRДень №${this.vars['daysCount']}BRРечь игрока №${this.vars['activeSpeaker'] + 1}`);
		return true;
	}
	stageCourt() {
		this.activeSpeakerChange();
		if (!this.playersFoulsCheck())
		{
			alert('На данном кругу был получен дисквалифицирующий фол. Голосование не проводится.');
			return false;
		}
		else if (this.vars.currentVote.length === 0)
		{
			alert('На голосование никто не выставлен. Голосование не проводится.');
			return false;
		}
		else if (this.vars.currentVote.length === 1)
		{
			let string = '';
			string = 'На голосование был выставлен лишь 1 игрок\r\n';
			if (this.vars['daysCount'] > 0) {
				string += `Наш город покидает игрок № ${this.vars.currentVote[0]}!\r\nУ вас есть 1 минута для последней речи`;
				console.log(`this.vars.currentVote[0]`);
				console.log(this.vars.currentVote[0]);
				this.playerOut(this.vars.currentVote[0] - 1, 2);
			}
			else
				string += 'Этого недостаточно для проведения голосования. Наступает фаза ночи!';
			alert(string);
			return false;
		}
		return true;
	}
	stageNight() {
		this.gameSetStatePhrase('Наступила ночь.BRГород спит.BRМафия - стреляет!');
		return true;
	}
	stageLastWill(index) {
		let str = '';
		if (this.players[index]['out'] === 1 && this.checkOwnerBestMove(index))
			str = 'BRУ вас есть право лучшего хода!';
		this.activeSpeakerChange(index);
		index = +index + 1;
		alert(`Игрок № ${index} покидает стол.
Прощальная минута игрока №${index}${str}`);
		this.gameSetStatePhrase(`Игрок № ${index} покидает стол.BRПрощальная минута игрока №${index}${str}`);
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
		const targetNum = +targetId + 1;
		if (this.vars['bestMove'].indexOf(targetNum) === -1) {
			targetRow.classList.add('for-best-move');
			this.vars['bestMove'].push(targetNum);
		}

		const ownerBMNumber = +this.vars['ownerBestMove'] + 1;
		if (this.vars['bestMove'].length === 3){
			if (confirm(`Игрок №${ownerBMNumber} назвал, игроками мафии, игроков, под номерами: ${this.vars['bestMove'].join(',')}?`))
				this.vars['ownerBestMove'] = false;
			else{
				this.vars['bestMove'] = [];
			}
			this.gameTable.querySelectorAll('tr.for-best-move').forEach(tableRow => tableRow.classList.remove('for-best-move'));
			document.body.querySelector('.best-move__text').textContent = `Игрока №${ownerBMNumber}: ${ this.vars['bestMove'].join(',')}`
		}
		this.gameSaveLog(`Игрок №${ownerBMNumber} назвал, игроками мафии, игроков, под номерами: ${this.vars['bestMove'].join(',')}?`);
	}
	actionPutPlayerOnTheVote(targetRow,playerVotedId=-1) {
		let targetId = targetRow.dataset.playerId;
		if (this.players[targetId]['out']>0){
			alert('Не принято!\r\nЗа столом нет такого игрока.');
			return false;
		}
		let act = playerVotedId;
		if (act === -1) {
			if (this.vars['timer'] === 6000)
				act = this.vars['prevActiveSpeaker'];
			else
				act = this.vars['activeSpeaker'];
		}
		if (act === -1) return false;
		const td = this.gameTable.querySelector(`tr[data-player-id="${act}"] td.vote-num`);
		if (td.textContent !== '' && targetId != parseInt(td.textContent)-1) return false;
		++targetId;
		const check = this.vars.currentVote.indexOf(targetId);
		if (check === -1){
			td.textContent = targetId;
			this.vars.currentVote.push(targetId);
			this.players[act]['puted'][this.vars['daysCount']] = targetId;
			targetRow.classList.add("for-vote");
			this.gameSaveLog(`Игрок №${act+1} выставил игрока № ${targetId} на голосование.`);
		}
		else{
			if (this.players[act]['puted'][this.vars['daysCount']] === targetId) {
				td.textContent = '';
				targetRow.classList.remove( "for-vote" );
				this.vars.currentVote.splice(check,1);
				this.players[act]['puted'][this.vars['daysCount']] = -1;
				this.gameSaveLog('Ошибочное выставление. Отмена!');
			}
			else{
				alert('Не принято!\r\nУже выстален.');
				this.gameSaveLog(`Игрок №${act+1} попытался выставить игрока № ${targetId}.BRНе принято - уже выставлен!`);
				return false;
			}
		}
		if (this.vars.currentVote.length>0)
			this.courtRoomShow();
		else this.courtRoomHide();
	}
	actionShootedPlayer(targetRow) {
		const targetId = targetRow.dataset.playerId;
		this.vars['kill'][this.vars['daysCount']].push(targetId);
		targetRow.classList.add('for-kill');
		this.gameSaveLog(`Стреляют в игрока №${targetId+1}.`);
	}
	activeSpeakerChange(id=-1)
	{
		this.gameTable.querySelectorAll('tr.active').forEach(td => td.classList.remove('active'));
		if (!isNaN(id) && id !== -1) {
			this.gameTable.querySelector(`tr[data-player-id="${id}"]`).classList.add('active');
			if (this.vars['activeSpeaker'] != id)
				this.vars['activeSpeaker'] = id;
		}
	}
	actionSetFoul(event){
		let tableRow = event.target.closest('tr');
		let index = parseInt(tableRow.dataset.playerId);
		if (this.players[index]['out']>0) return false;
		if (event.target.dataset.foulId !== "3")
		{
			++this.players[index]['fouls'];
			if (this.players[index]['fouls']===3)
			{
				this.players[index]['muted'] = 1;
				tableRow.querySelector('[data-foul-id="2"] i.fa-microphone-slash').classList.remove('hidden');
				tableRow.querySelector('td.prim').textContent = 'Молчит';
				this.gameSaveLog(`Игрок №${+index + 1} получает свой третий фолл и пропускает свою следующую речь!`);
			
			}
			if (this.players[index]['fouls']=== 4)
			{
				if (confirm(`Игрок №${+index+1} покидает стол по 4-му фолу?`))
				{
					tableRow.querySelectorAll('td[data-foul-id]').forEach(foulsCell => foulsCell.classList.add('fail'));
					this.playerOut(index, 3);
					mafiaTimer.pause();
					this.gameSaveLog(`Игрок №${+index + 1} получает свой 4-й фол и покидает игру!`);
					this.checkWinner();
					return true;
				}
				else --this.players[index]['fouls'];
			}
			tableRow.querySelectorAll('td[data-foul-id]').forEach(foulsCell => {
				if (foulsCell.dataset.foulId < this.players[index]['fouls'])
					foulsCell.classList.add('fail')
				else return;
			});
			this.gameSaveLog(`Игрок №${+index + 1} получает свой ${players[index]['fouls']}' фол!`);
		}
		else if (confirm(`Игрок №${+index+1} покидает стол по дисквалифицирующему фолу?`))
		{
			this.players[index]['fouls'] = 5;
			tableRow.querySelectorAll('td[data-foul-id]').forEach(foulsCell => foulsCell.classList.add('fail'));
			this.playerOut(index, 4);
			this.gameSaveLog(`Игрок №${+index + 1} покидает стол по дисквалифицирующему фолу!`);
			this.checkWinner();
		}
		else return false;
		this.checkWinner();
	}
	playersFoulsCheck(){
		let i = -1;
		while(++i<=9)
		{
			if (this.players[i]['fouls'] < 3 || this.players[i]['out'] < 3 || this.players[i]['out'] > 3 && this.players[i]['muted'] !== 1) continue;
			return false;
		}
		return true;
	}
	courtRoomClear(next=1){
		if (!load)
			this.vars.currentVote.length=0;
		this.courtRoomHide();
		this.gameTable.querySelectorAll('tr.for-vote,td.vote-num').forEach(element => {
			if (element.classList.contains('for-vote'))
				element.classList.remove('for-vote')
			if (element.classList.contains('vote-num'))
				element.textContent = '';
		});
		this.activeSpeakerChange();
		if (next === 1) this.nextStage();
	}
	courtRoomHide() {
		const courtRoom = document.body.querySelector('.game__players-on-vote')
		courtRoom.innerHTML = '';
		courtRoom.classList.add("hidden");
	}
	courtRoomShow() {
		const courtRoom = document.body.querySelector('.game__players-on-vote')
		courtRoom.innerHTML = `На голосование выставлены игроки под номерами: ${this.vars.currentVote.join(', ')}.`
		courtRoom.classList.remove("hidden");
	}
	courtRoomAction(debatesMode = 0) {
		this.gameSetStatePhrase('Зал суда.BRПросьба убрать руки от стола, прекратить жестикуляцию и агитацию.BRНа '+(debatesMode===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+this.vars.currentVote.join(', '));
		alert('Уважаемые игроки, переходим в зал суда!\r\nНа '+(debatesMode===0 ? 'голосовании' : 'перестрелке')+' находятся следующие игроки: '+this.vars.currentVote.join(', '));
		let i=-1,votes_available=0,players_count=0,vote=0,voted=[],cv=[],prev_max=0,debate=[], string='';
		votes_available = players_count = this.playerGetActiveCount();
		i=-1;
		while(++i<this.vars.currentVote.length)
		{
			cv.push(this.vars.currentVote[i]);
			if (votes_available < 1) 
			{
				voted.push(0)
				string+=`Игрок  №${this.vars.currentVote[i]}\tГолоса: 0BR`;
				continue;
			}
			vote= i < this.vars.currentVote.length-1 ? parseInt(prompt(this.vars.currentVote[i]+'! Кто за то, что бы наш город покинул игрок под № '+this.vars.currentVote[i],'0')) : votes_available;
			string+=`Игрок  № ${this.vars.currentVote[i]}\tГолоса: ${vote};BR`;
			voted.push(vote);
			votes_available -= vote;
			if (vote===0 || i===0) continue;
			if (voted[prev_max]<vote)
			{
				prev_max = i;
				if (debate.length!==0) debate.length=0;
			}
			else if (voted[prev_max]===vote)
			{
				if (debate.length===0) debate.push(this.vars.currentVote[prev_max],this.vars.currentVote[i]);
				else debate.push(this.vars.currentVote[i]);
			}
		}
		string = 'Голоса распределились следующим образом:BR'+string;
		if (debate.length===0)
		{
			string += 'Нас покидает Игрок под № ' + this.vars.currentVote[prev_max] + '.BRУ вас прощальная минута.';
			this.playerOut(this.vars.currentVote[prev_max]-1,2);
		}
		else
			string+='В нашем городе перестрелка. Между игроками под номерами: '+debate.join(', ');
		alert(string.replace(/BR/g, '\r\n'));
		this.gameTable.querySelectorAll('tr.for-vote').forEach(item => item.classList.remove('for-vote'));

		if (debatesMode > 0 && debate.length === this.vars.currentVote.length)
		{
			if (players_count > 4)
			{
				vote = parseInt(prompt(`Кто за то, что все игроки под номерами: ${debate.join(', ')} покинули стол?`, '0'));
				if ( vote > players_count/2)
				{
					string=`Большинство (${vote} из ${players_count}) - за!BRИгроки под номерами: ${debate.join(', ')} покидают стол.`;
					i=-1;
					while(++i<debate.length)
						this.playerOut(debate[i]-1,2);
				}
				else 
					string=`Большинство (${vote} из ${players_count}) - - против!BRНикто не покидает стол.`;
			}
			else 
				string='При количестве игроков менее 5 нельзя поднять 2 и более игроков.BRНикто не покидает стол.';
			alert(string.replace(/BR/g, '\r\n'));
			this.gameSaveLog(string);
			debate.length=0;
		}
		if (debate.length > 0)
		{
			this.vars.currentVote = Array.from(debate);
			this.courtRoomDebate();
		}
		else
			this.courtRoomClear(1)
	}
	courtRoomDebate() {

		const playerId = this.vars.currentVote[++this.vars['debaters']] - 1;
		this.gameSetStatePhrase(`Фаза перестрелки.BRРечь игрока № ${+playerId+1}`);
		if (!isNaN(playerId) && playerId !== -1)
		{
			this.activeSpeakerChange(playerId);
			if (this.vars['timer'] === 6000) 
			{
				this.vars['timer'] = 3000;
				document.body.querySelector('div.timer__watchclock').textContent = inttotime(this.vars['timer']);
			}
		}
		else
		{
			this.courtRoomAction(1);
		}
	}
	playerOut(id, reason) {
		let tableRow = this.gameTable.querySelector(`tr[data-player-id="${id}"]`);
		tableRow.classList.add('out');
		tableRow.querySelector(`td.prim`).textContent = this.reasons[reason];
		this.players[id]['out'] = reason;
		if (reason < 3) this.vars['lastWill'].push(id);
		else this.players[id]['muted'] = 1;
		this.gameSaveLog(`Игрок №${id+1}' покидает наш город. Причина: ${this.reasons[reason]}!`);
		return this.vars['lastWill'];
	}
	playerGetActiveCount(role=0)
	{
		let playersCount = 0, index = -1;
		while(++index <= 9)
		{
			if (this.players[index]['out']>0) continue;
			if (role===2 && (this.players[index]['role'] === 0 || this.players[index]['role'] === 4)) continue; // Если ищем мафов - отсекаем миров
			if (role===1 && (this.players[index]['role'] === 1 || this.players[index]['role'] === 2)) continue; // Если ищем миров - отсекаем мафов
			++playersCount;
		}
		return playersCount;
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
			index = -1;
			const roles = ['', 'mafia', 'don', 'sheriff'];
			while(++index<=9)
			{
				if (this.players[index]['role'] === 0) continue;
				this.gameTable.querySelector(`tr[data-player-id="${index}"]`).classList.add(roles[this.players[index]['id']]);
			}
			this.gameSetStatePhrase('Поздравляем с победой: '+string);
			alert("Поздравляем с победой: "+string.replace(/BR/g,'\r\n'));
			this.vars['stage'] = 'finish';
			this.gameSaveLog(`Игра окончена! Поздравляем с победой - ${string}!`);
			// save_progress();
			return true;
		}
		return false;
	}
	playersGetSpeakersArray() {
		let index = this.vars['daysCount'], circle = 0, playerId = 0;
		for(;;)
		{
			if (++index > 10) {
				index = 0;
				++circle;
			}
			if (index === this.vars['daysCount']) break;
			playerId = index - 1 + circle;
			if (this.players[playerId]['out'] > 0) continue;
			this.vars['daySpeakers'].push(playerId);
		}
		return this.vars['daySpeakers'];
	}
	playerGetNextSpeaker()
	{
		let index = this.vars['daySpeakers'].shift();
		if (this.players[index]['out'] > 2 && this.players[index]['muted'] === 1)
		{
			this.playerUnmute(index);
			return this.playerGetNextSpeaker();
		}
		else if (this.players[index]['out'] > 0)
			return this.playerGetNextSpeaker();
		else
		{
			if (this.players[index]['muted'] !== 1)
				return index;
			else 
			{
				if (this.playerGetActiveCount() < 5) 
				{
					this.vars['timer'] = 3000;
					this.playerUnmute(index);
					return index;
				}
				let put = parseInt(prompt('Игрок №'+(index+1)+' молчит, но может выставить кандидатуру: ','0'));
				if (put > 0)
					this.actionPutPlayerOnTheVote(document.body.querySelector(`tr[data-player-id="${(put - 1)}"]`), index);
				return this.playerGetNextSpeaker();
			}
		}	
	}
	playerUnmute(id)
	{
		const tableRow = this.gameTable.querySelector(`tr[data-player-id="${id}"]`);
		tableRow.querySelector(`td[data-foul-id=3] > i.fa-microphone-slash`).style.display='none';
		const prim = tableRow.querySelector(`td.prim`);
		if (prim.textContent === 'Молчит')
			prim.textContent='';
		this.players[id]['muted'] = 0;
		this.gameSaveLog('Промах! Никто не был убит этой ночью.');
	}
	playerGetShooted(){
		this.gameTable.querySelectorAll('tr.for-kill').forEach(item => item.classList.remove('for-kill'));
		if (this.vars['daysCount'] >= 0)
		{
			if (this.vars['kill'][this.vars['daysCount']].length === 1)
			{
				this.playerOut(this.vars['kill'][this.vars['daysCount']][0], 1);
				this.gameSaveLog(`Игрок № ${this.vars['kill'][this.vars['daysCount']][0]} - убит!`);
				return true;
			}
			else {
				alert('Промах! Никто не был убит этой ночью.');
				this.gameSaveLog('Промах! Никто не был убит этой ночью.');
			}
		}
		return false;
	}
	checkOwnerBestMove(lw){
		let check=0;
		let i=-1;
		while(++i<=9)
		{
			if (this.players[i]['out']===1 || this.players[i]['out']===2)
				++check;
			if (check > 1) break;
		}
		if (check==1)
		{
			this.vars['ownerBestMove'] = lw;
			this.vars['ownerCanSetBestMove'] = true;
			return true;
		}
		return false;
	}
}