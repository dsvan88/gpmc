actionHandler = {
	login: function (modal) {
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=login&login=" + modal.querySelector("input[name=login]").value + "&pass=" + modal.querySelector("input[name=pass]").value,
			success: function (res) {
				res = JSON.parse(res);
				if (res["error"] == 0) location.reload();
				else alert(res["txt"]);
			},
			error: function (res) {
				alert("Error: Ошибка связи с сервером");
			},
		});
	},
	userRegister: function (modal) {
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=user-registration&" + $("form#RegisterForm").serialize(),
			success: function (res) {
				res = JSON.parse(res);
				if (res["error"] == 0) {
					alert(res["txt"]);
				} else {
					alert(res["txt"]);
					$("input[name=" + res["wrong"] + "]").trigger("focus");
				}
			},
			error: function (res) {
				alert("Error: Ошибка связи с сервером");
			},
		});
		return false;
	},
	addGamers: function (target) {
		let newID = document.body.querySelectorAll(".gamer").length;
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=gamer-field&i=" + newID,
			success: function (res) {
				eveningGamersFields.insertAdjacentHTML("beforeend", res);
				$("input.input_name").autocomplete({
					source: "switcher.php?need=autocomplete_names&e=" + EveningID + "&",
					minLength: 2,
				});
				$(".timepicker").datetimepicker({ datepicker: false, format: "H:i" });
			},
		});
	},
	setEveningData: function (target) {
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data:
				"need=apply_evening&eve_date=" +
				document.body.querySelector("input[name=eve_date]").value +
				"&eve_place=" +
				document.body.querySelector("input[name=eve_place]").value +
				"&eve_place_info=" +
				document.body.querySelector("input[name=eve_place_info]").value,
			success: function (res) {
				alert("Успешно!" + res);
			},
			error: function (res) {
				alert("Error: Ошибка связи с сервером");
			},
		});
	},
	approveEvening: function (target) {
		let data = [];
		let names = eveningRegisterForm.querySelectorAll("input[name=gamer]");
		let arrives = eveningRegisterForm.querySelectorAll("input[name=arrive]");
		let durations = eveningRegisterForm.querySelectorAll("select[name=duration]");
		for (let x = 0; x < names.length; x++) {
			data.push({
				name: names[x].value,
				arrive: arrives[x].value,
				duration: durations[x].value,
			});
		}
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data:
				"need=apply_evening&eve_date=" +
				document.body.querySelector("input[name=eve_date]").value +
				"&eve_place=" +
				document.body.querySelector("input[name=eve_place]").value +
				"&eve_place_info=" +
				document.body.querySelector("input[name=eve_place_info]").value +
				"&data=" +
				JSON.stringify(data),
			success: function (res) {
				alert("Успешно!" + res);
			},
			error: function (res) {
				alert("Error: Ошибка связи с сервером");
			},
		});
	},
	eveningPlace: function (event) {
		console.log("catch Event: " + "need=get_place_info&p=" + event.target.value);
		$.ajax({
			url: "switcher.php",
			type: "POST",
			data: "need=get_place_info&p=" + event.target.value,
			success: function (res) {
				document.body.querySelector('input[name="eve_place_info"]').value = res;
			},
			error: function (res) {
				console.log("Error: Ошибка связи с сервером");
			},
		});
	},
	eveningGamersFields: function (target) {
		if (target.tagName === "IMG") {
			let elem = target.closest("span");
			if (elem.className === "img-delete" && confirm("Точно удалить игрока из записи?")) {
				$.ajax({
					url: "switcher.php",
					type: "POST",
					data: "need=discharge_gamer&i=" + elem.id.split("_")[0],
					success: function (res) {
						location.reload();
					},
				});
			}
		}
	},
	clickCommonHandler: function (event) {
		let target = event.target;
		if (target.tagName === "IMG") target = target.closest("a,div");
		if ("formType" in target.dataset) {
			column = target.dataset.editRow || "";
			$.ajax({
				url: "switcher.php",
				type: "POST",
				data: "need=" + target.dataset.formType + "_form" + (column !== "" ? "&c=" + column : ""),
				success: function (res) {
					res = JSON.parse(res);
					if (res["error"] != 0) {
						alert(res["html"]);
						return false;
					}
					let modal = modalEvent(res["html"]);
					$(".modal-body input.input_name").autocomplete({
						source: "switcher.php?need=autocomplete_names",
						minLength: 2,
					});
					$(".modal-body .datepick").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });
					$(".modal-body .timepicker").datetimepicker({ datepicker: false, format: "H:i" });
					let type = camelize(target.dataset.formType);
					modal.querySelector("form").addEventListener("submit", (submitEvent) => {
						submitEvent.preventDefault();
						actionHandler[type](modal);
					});
				},
				error: function (res) {
					alert("Error: Ошибка связи с сервером");
				},
			});
			return false;
		} else if ("action" in target.dataset) {
			$.ajax({
				url: "switcher.php",
				type: "POST",
				data: "need=" + target.dataset.action,
				success: function (res) {
					res = JSON.parse(res);
					if (res["error"] != 0) {
						alert(res["html"]);
						return false;
					}
					location.reload();
				},
				error: function (res) {
					alert("Error: Ошибка связи с сервером");
				},
			});
			return false;
		} else if ("actionType" in target.dataset) {
			let type = camelize(target.dataset.actionType);
			try {
				event.preventDefault();
				actionHandler[type](target);
			} catch (error) {
				alert(`Не существует метода для этого action-type: ${type}... или возникла ошибка. Сообщите администратору!\r\n${error.name}: ${error.message}`);
				console.log(error);
			}
		}
	},
};

function open_log(id) {
	$("#Log_" + id).removeClass("hide");
	$("#ShowLog_" + id).text("- Скрыть лог игры");
}
function close_log(id) {
	$("#Log_" + id).addClass("hide");
	$("#ShowLog_" + id).text("+ Открыть лог игры");
}
function check_present(name) {
	let players = document.body.querySelectorAll("span.player_name");
	for (item of players) {
		if (item.childNodes[0].data === name) return true;
	}
	return false;
}
function rename_player(name) {
	$.ajax({
		url: "switcher.php",
		type: "POST",
		data: "need=rename_player_form&n=" + name,
		success: function (res) {
			modalEvent(res);
			$("input.input_gamer").autocomplete({
				source: "switcher.php?need=autocomplete_names&",
				minLength: 2,
			});
		},
	});
}
function add_evening_player(name) {
	$.ajax({
		url: "switcher.php",
		type: "POST",
		data: "need=add_new_player&n=" + name,
		success: function (res) {
			$("#PlayersArray").html($("#PlayersArray").html() + res);
		},
	});
}
function add_player(name) {
	let i = -1;
	let set = false;
	while (++i <= 9) {
		if ($('input[name="player[' + i + ']"]').val() === "") {
			$('input[name="player[' + i + ']"]').val(name);
			set = true;
			break;
		}
	}
	if (!set) $('input[name="manager"]').val(name);
}
function inttotime(t) {
	m = Math.floor(t / 6000);
	s = Math.floor((t % 6000) / 100);
	ms = t % 100;
	return "0" + m + ":" + (s > 9 ? s : "0" + s) + ":" + (ms > 9 ? ms : "0" + ms);
}
function redirectPost(url, data) {
	var form = document.createElement("form");
	document.body.appendChild(form);
	form.method = "post";
	form.action = url;
	for (var name in data) {
		var input = document.createElement("input");
		input.type = "hidden";
		input.name = name;
		input.value = data[name];
		form.appendChild(input);
	}
	form.submit();
}
function modalEvent(html = "", divId = "modalWindow") {
	let [modalOverlay, modal, modalBody] = prepeareModalWindow(divId);
	if (html !== "") modalBody.innerHTML = html;
	let modalsAll = document.body.querySelectorAll(".modal-body");
	let overlay = document.body.querySelector("#overlay");
	$(overlay).fadeIn(
		400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		function () {
			// пoсле выпoлнения предыдущей aнимaции
			modalOverlay.style.zIndex = 4 + modalsAll.length;
			$(modal)
				.css({
					display: "block",
				})
				.animate({ opacity: 1, top: "50%" }, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		}
	);
	return modal;
}
function prepeareModalWindow(divId = "modalWindow") {
	let overlay = document.body.querySelector("#overlay");
	if (overlay === null) {
		let overlay = document.createElement("div");
		overlay.id = "overlay";
		document.body.append(overlay);
	}
	let modalOverlay = document.createElement("div");
	modalOverlay.className = "modal-overlay modal-close";

	let modal = document.createElement("div");
	modal.className = "modal";
	modal.id = divId;

	let modalBody = document.createElement("div");
	modalBody.className = "modal-body";

	modal.append(modalBody);
	modalOverlay.append(modal);

	document.body.append(modalOverlay);
	modalOverlay.addEventListener("click", closeModalWindow);
	return [modalOverlay, modal, modalBody];
}
function closeModalWindow(event) {
	if (!event.target.classList.contains("modal-close")) return;
	let modalOverlay = event.target.closest(".modal-overlay");
	let modalsAll = document.body.querySelectorAll(".modal-body");
	$(modalOverlay).animate(
		{ opacity: 0, top: "45%" },
		200, // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
		function () {
			// пoсле aнимaции
			$(this).css("display", "none"); // делaем ему display: none;
			if (modalsAll.length === 1) $("#overlay").fadeOut(400); // скрывaем пoдлoжку
			modalOverlay.removeEventListener("click", closeModalWindow);
			modalOverlay.remove();
		}
	);
}
function prepeare_add_modal_div(new_id) {
	$("body").append('<div id="' + new_id + '" class="modal_window">' + $("div#modal_block").html() + "</div>");
}
function make_cropper() {
	let img = $("#img_for_crop");
	img.cropper({
		aspectRatio: 3.5 / 4,
		minContainerWidth: 325,
		minContainerHeight: 220,
		checkOrientation: false,
		ready: function (event) {
			$("body").on("click", "#CropMyAvatar", function () {
				$.ajax({
					url: "switcher.php",
					data: "need=crop_file&i=" + $("input[name=filename]").val() + "&d=" + JSON.stringify(img.data("cropper").getData(true)),
					type: "POST",
					success: function (res) {
						res = JSON.parse(res);
						if (res["error"] === 0) location.reload();
						else alert(res["html"]);
					},
					error: function (res) {
						alert("Error: Ошибка связи с сервером");
					},
				});
			});
		},
	});
}
function became_admin() {
	$.ajax({
		url: "switcher.php",
		type: "POST",
		data: "need=admin_login_form",
		success: function (res) {
			if (res === "admin") {
				location.reload();
				return false;
			} else {
				modalEvent(res);
				$(".modal_window").off("click", "#LogInButton");
				$(".modal_window").on("click", "#LogInButton", function () {
					$.ajax({
						url: "switcher.php",
						type: "POST",
						data: "need=login&" + $("#Form_AdminLogin").serialize(),
						success: function (res) {
							let result = JSON.parse(res);
							if (result["error"] == $('#Form_AdminLogin input[name="ap"]').val()) location.reload();
							else alert(result["txt"]);
						},
						error: function (res) {
							alert("Error: Ошибка связи с сервером");
						},
					});
					return false;
				});
			}
		},
		error: function (res) {
			alert("Error: Ошибка связи с сервером");
		},
	});
}

function camelize(str) {
	return str
		.split("-") // разбивает 'my-long-word' на массив ['my', 'long', 'word']
		.map((word, index) => (index == 0 ? word : word[0].toUpperCase() + word.slice(1)))
		.join(""); // соединяет ['my', 'Long', 'Word'] в 'myLongWord'
}
Array.prototype.shuffle = function (b) {
	var i = this.length,
		j,
		t;
	for (let i = this.length - 1; i > 0; i--) {
		let j = Math.floor(Math.random() * (i + 1)); // случайный индекс от 0 до i
		[this[i], this[j]] = [this[j], this[i]];
	}
	return this;
};