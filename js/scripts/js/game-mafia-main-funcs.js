class MafiaMainFuncs {
	prevVars = [];
	prevPlayers = [];
    prevText = [];
	maxStepBacks = 10;
	MainTimer;
	load = false;
	gameTable = null;
	timerDiv = null;
	gameId = -1;
	players = {};
	vars = {};
	// playersShooted = [];
	gameTable = null;
	timerDiv = null;
	reasons = ['','Убит','Осуждён','4 Фола','Дисквал.'];
    gameSetStatePhrase(string)
    {
        this.caption = string.replace(/BR/g, '<br>');
        this.HeaderTextBlock.innerHTML = this.caption;
        this.gameSaveLog(string);
	}
    gamePrepeareUndo(){
		if (this.prevVars.length === this.maxStepBacks){
			this.prevVars.shift()
			this.prevPlayers.shift()
			this.prevText.shift()
		}
		this.prevVars.push(JSON.stringify(this.vars));
		this.prevPlayers.push(JSON.stringify(this.players));
		this.prevText.push(this.caption);
		if (this.prevVars.length === 1){
			let unDoBotton = this.timerDiv.querySelector('[data-timer-action="undo"].disabled');
			if (unDoBotton !== null)
					unDoBotton.classList.remove('disabled');
		}
    }
    gameActionUndo() {
        this.load = true;
        this.vars = JSON.parse(this.prevVars.pop());
        this.players = JSON.parse(this.prevPlayers.pop());
        this.caption = this.prevText;
        this.stateLoad();
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
                    // console.log(result);
                } else alert(result["text"]);
                /* if (res === '') return false;
                players = JSON.parse(res);
                let i = -1;
                while(++i<=9)
                    $('tr#'+i+'_'+players[i]['id']+' td.player_name > span.points').html(players[i]['points']+'&nbsp;').addClass((players[i]['points'] > 0.0 ? 'positive' : 'negative')); */
            },
        });
    }
    stateLoad() {
        this.courtRoomClear(0)
        //------------------------------------------ Обнуление состояний игроков
        this.gameTable.querySelectorAll('tr').forEach(tableRow => {
            tableRow.classList.remove('active');
            tableRow.classList.remove('out');
            tableRow.classList.remove('for-vote');
            tableRow.classList.remove('for-kill');
            tableRow.querySelectorAll('td').forEach(tableCell => {
                tableCell.classList.remove('fail');
                tableCell.classList.remove('for-best-move');
                if (tableCell.classList.contains('prim')) {
                    tableCell.textContent = '';
                }
                if (tableCell.dataset.foulId == "2") {
                    tableRow.querySelector('i.fa-microphone-slash').classList.add("hidden");
                }
            })
        })
        //------------------------------------------ Применение загруженных состояний игроков
        let index, check = this.vars.currentVote.length;
        if (check > 0) {
            this.courtRoomShow();
            index = -1;
            while (++i < check) {
                this.gameTable.querySelector(`tr[data-player-id="${this.vars.currentVote[index] - 1}"]`).classList.add('for-vote');
                let x = -1;
                while (++x <= 9)
                    if (this.players[x]['puted'][this.vars['daysCount']] == this.vars.currentVote[index]) break;
                this.gameTable.querySelector(`tr[data-player-id="${x}"] td.vote-num`).classList.add('for-vote');
                this.gameTable.querySelector(`tr[data-player-id="${x}"] td.vote-num`).textContent = this.vars.currentVote[index];
            }
        }
        this.gameTable.querySelector(`tr[data-player-id="${this.vars['activeSpeaker']}"]`).classList.add('active');
        index = -1;
        while (++index <= 9) {
            let tableRow = this.gameTable.querySelector(`tr[data-player-id="${index}"]`);
            for (let x = 1; x <= 4; x++)
                if (this.players[index]['foul'] >= x) {
                    let foulCell = tableRow.querySelector(`td[data-foul-id="${x}"]`);
                    foulCell.classList.add('fail');
                    if (this.players[index]['foul'] === 3 && this.players[index]['muted'] === 1) {
                        tableRow.querySelector(`td[data-foul-id="${x}"] i.fa-microphone-slash`).classList.add('hidden');
                        tableRow.querySelector(`td.prim`).textContent = 'Молчит';
                    }
                }
                else break;
            if (this.players[index]['out'] > 0) {
                tableRow.classList.add('out');
                tableRow.querySelector('td.prim').textContent = this.reasons[this.players[index]['out']];
            }
        }
        if (this.prevVars.length === 0)
            timerDiv.querySelector('[data-timer-action="undo"]').classList.add('disabled');
    }
    gameEnd() {
        if ((w = parseInt(prompt('Вы уверены, что пора прекратить игру?\r\nВведите победителя: 1 (Мирные), 2 (Мафия), 3 (Ничья), 0 - отмена', '0'))) > 0) {
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
                    _self.gameSetStatePhrase(_self.vars.caption);
                } else alert(result["text"]);
            },
        });
    }
    gameSaveLog(string) {
        let spanElement = document.createElement('div');
        spanElement.textContent = string.replace(/(BR|\<br\>|\<\/br\>)/g, ' ');
        this.gameLogBlock.append(spanElement);
    }
}