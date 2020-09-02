actionHandler.shuffleGamers = function (target) {
	let players = [],
		i = -1;
	let elems = document.body.querySelectorAll("input[name=player]");
	elems.forEach((item) => players.push(item.value));
	players.shuffle().forEach((item, index) => (elems[index].value = item));
};
actionHandler.addPlayersToArray = function (modal) {
	let name = document.body.querySelector("form.add-player-to-array-form input.input_name[name=gamer]").value.trim();
	if (name === "") return false;
	let gamers = document.body.querySelectorAll("span.player_name");

	let present = false;
	for (item of gamers) {
		if (item.childNodes[0].data === name) {
			present = true;
			break;
		}
	}
	if (!present) {
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=add_gamer&n=" + name,
			success: function (userId) {
				let newGamer = gamers[0].cloneNode(true);
				newGamer.dataset.playerId = userId;
				newGamer.childNodes[0].data = name;
				if (newGamer.classList.contains("selected")) newGamer.classList.remove("selected");
				gamers[0].parentElement.append(newGamer);
			},
		});
	} else alert("Уже зарегистрирован!");
};
actionHandler.toggleGamerInTable = function (target) {
	let name = target.childNodes[0].data;
	let playersList = document.body.querySelectorAll("input[name=player],input[name=manager]");
	let toogled = false;
	if (target.classList.contains("selected")) {
		for (player of playersList) {
			if (player.value === name) {
				player.value = "";
				toogled = true;
				break;
			}
		}
	} else {
		for (player of playersList) {
			if (player.value === "") {
				player.value = name;
				toogled = true;
				break;
			}
		}
	}
	if (toogled) target.classList.toggle("selected");
};
actionHandler.removeGamer = function (target) {
	let gamer = target.parentElement;
	let gamerName = gamer.childNodes[0].data;
	if (
		confirm(`Точно удалить игрока ${gamerName} из записи?
    Заработанные им сегодня балы могут не учитываться в статистике!`)
	) {
		let gamerId = gamer.dataset.playerId;
		let playersList = document.body.querySelectorAll("input[name=player],input[name=manager]");
		for (player of playersList) {
			if (player.value === gamerName) {
				player.value = "";
				break;
			}
		}
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=remove-gamer&i=" + gamerId,
			success: function (res) {
				gamer.remove();
			},
		});
	}
};
actionHandler.startGame = function (target) {
	if (document.body.querySelector("input[name=manager]").value.trim() === "") {
		alert("Сначала выберите ведущего из списка не играющих игроков!");
		return false;
	}
	let check = 0;
	let playersList = document.body.querySelectorAll("input[name=player]");
	let setupRoles = ["1", "1", "2", "4", "0", "0", "0", "0", "0", "0"];
	let role = 0;
	for (player of playersList) {
		if (player.value.trim() === "") {
			check = 1;
			alert("Кого-то не хватает! (Нет игрока под №" + (i + 1) + ")");
			break;
		}
		role = player.nextElementSibling.value;
		let index = setupRoles.indexOf(role);
		if (index === -1) {
			check = 1;
			break;
		} else {
			setupRoles.splice(index, 1);
		}
	}
	if (setupRoles.length > 0) check = 1;
	if (check === 0) {
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=game_start&" + $("#tempForm").serialize() + "&e=" + EveningID,
			success: function (res) {
				window.location.href = "/?g_id=" + res;
			},
		});
	} else {
		alert("Неправильно распределены роли!\r\n(Ролей всего: Мафии - 2, Дон - 1, Шериф - 1, Мирные - 6");
	}
};
actionHandler.resumeGame = function (target) {
	window.location.href = "/?g_id=" + target.dataset.gameId;
};
