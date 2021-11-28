let isLoadEvening = true;
actionHandler.playersShuffle = function (target, event) {
	let players = [],
		i = -1;
	let elems = document.body.querySelectorAll("input[name='player[]']");
	elems.forEach((item) => players.push(item.value));
	players.shuffle().forEach((item, index) => (elems[index].value = item));
};
actionHandler.playerRemove = function (target) {
	let gamer = target.parentElement;
	let gamerName = gamer.childNodes[0].data;
	if (
		confirm(`Точно удалить игрока ${gamerName} из записи?
    Заработанные им сегодня балы могут не учитываться в статистике!`)
	) {
		let gamerId = gamer.dataset.playerId;
		let playersList = document.body.querySelectorAll('input[name="player[]"],input[name=manager]');
		for (player of playersList) {
			if (player.value === gamerName) {
				player.value = "";
				break;
			}
		}
		postAjax({
			data: `{"need":"do_player-remove-from-booking","id":"${gamerId}"}`,
			successFunc: function (userId) {
				gamer.remove();
			},
		});
	}
};
actionHandler.playerToggleInTable = function (target) {
	const name = target.childNodes[0].data;
	const playersList = document.body.querySelectorAll("input[name='player[]'],input[name=manager]");
	if (target.classList.contains("selected")) {
		for (player of playersList) {
			if (player.value === name) {
				player.value = "";
				target.classList.toggle("selected");
				break;
			}
		}
	} else {
		for (player of playersList) {
			if (player.value === "") {
				player.value = name;
				target.classList.toggle("selected");
				break;
			}
		}
	}
};
actionHandler.playerArrayToggle = function (target) {
	let playersList = [];
	document.body.querySelectorAll("input[name='player[]'],input[name=manager]").forEach(item => item.value !== '' ? playersList.push(item.value) : false);
	const participantsList = document.body.querySelectorAll(".game-prepeare__players-array .player__name");

	participantsList.forEach(participant => {
		if (playersList.includes(participant.childNodes[0].data))
			participant.classList.add('selected');
		else
			participant.classList.remove('selected');
	});
};
actionHandler.gameBegin = function (target) {
	if (document.body.querySelector("input[name=manager]").value.trim() === "") {
		alert("Спочатку оберіть ведучого з списку очікування гравців!");
		return false;
	}
	const playersList = document.body.querySelectorAll("input[name='player[]']");
	const setupRoles = ["1", "1", "2", "4", "0", "0", "0", "0", "0", "0"];
	let role = 0;
	for (player of playersList) {
		if (player.value.trim() === "") {
			alert("Місце гравця игрока №" + (i + 1) + " - порожнє!");
			return false;
		}
		role = player.nextElementSibling.value;
		let index = setupRoles.indexOf(role);
		if (index === -1) {
			alert("Невірно обрані ролі гравців!\r\n(У колоді всього: Мафії - 2, Дон - 1, Шеріф - 1, Мирні - 6");
			return false;
		} else {
			setupRoles.splice(index, 1);
		}
	}
	if (setupRoles.length === 0) {
		const form = document.body.querySelector("#beReadyForGame")
		const formData = new FormData(form);
		formData.append('need', 'do_game-begin');
		formData.append('evening', form.querySelector("div[data-evening-id]").dataset.eveningId);
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				window.location.href = "/?gid=" + result['gid'];
			},
		});
	}
};
actionHandler.gameResume = function (target) {
	window.location.href = "/?gid=" + target.dataset.gameId;
};
/*
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
		postAjax({
			data: {
				need: "add_gamer",
				name: name,
			},
			successFunc: function (userId) {
				let newGamer = gamers[0].cloneNode(true);
				newGamer.dataset.playerId = userId;
				newGamer.childNodes[0].data = name;
				if (newGamer.classList.contains("selected")) newGamer.classList.remove("selected");
				gamers[0].parentElement.append(newGamer);
			},
		});
	} else alert("Уже зарегистрирован!");
};
//-------------------Проверить!
actionHandler.renameGamer = function (modal) {
	let data = serializeForm(modal);
	data["need"] = "rename-gamer";
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] != 0) {
				alert(result["txt"]);
				return false;
			}
			alert(result["txt"]);
			let gamer = document.body.querySelector(`span.temp_username[data-edit-target=${result["uid"]}]`);
			gamer.classList.remove("temp_username");
			gamer.dataset.removeAttribute("data-form-type");
			gamer.dataset.removeAttribute("data-edit-target");
			gamer.dataset.actionType = "toggle-gamer-in-table";
			gamer.dataset.playerId = result["uid"];
			if (confirm(`Добавить игрока ${result["newName"]} в список играющих на ближайшую игру?]`)) actionHandler.toggleGamerInTable(gamer);
		},
	});
};

*/
