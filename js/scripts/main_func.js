let debug = true;
actionHandler = {
	adminPanel: function (target, event) {
		if (event.ctrlKey || event.metaKey || target.dataset.actionMode === "admin") {
			postAjax({
				data: {
					need: "admin-panel",
				},
				successFunc: function (result) {
					result = JSON.parse(result);
					if (result["error"] == 0) location.reload();
					else alert(result["txt"]);
				},
			});
		} else window.location.href = target.href;
	},
	login: function (modal) {
		let data = serializeForm(modal);
		data["need"] = "login";
		postAjax({
			data: data,
			successFunc: function (result) {
				result = JSON.parse(result);
				if (result["error"] == 0) location.reload();
				else alert(result["txt"]);
			},
		});
	},
	headerLogin: function (target) {
		postAjax({
			data: {
				need: "login",
				login: document.body.querySelector("header input[name=login]").value,
				pass: document.body.querySelector("header input[name=pass]").value,
			},
			successFunc: function (result) {
				result = JSON.parse(result);
				if (result["error"] == 0) location.reload();
				else alert(result["txt"]);
			},
		});
	},
	userRegister: function (modal) {
		let data = serializeForm(modal);
		data["need"] = "user-registration";
		if (data["chk_pass"] !== data["pass"]) {
			modal.querySelector("input[name=pass]").focus();
			alert("Пароли не совпадают!");
			return false;
		}
		postAjax({
			data: data,
			successFunc: function (result) {
				result = JSON.parse(result);
				if (result["error"] == 0) {
					alert(result["txt"]);
				} else {
					alert(result["txt"]);
					modal.querySelector(`input[name=${result["wrong"]}]`).focus();
				}
			},
		});
	},
	addGamers: function (target) {
		let newID = document.body.querySelectorAll(".gamer").length;
		postAjax({
			data: {
				need: "gamer-field",
				id: newID,
			},
			successFunc: function (result) {
				eveningGamersFields.insertAdjacentHTML("beforeend", result);
				$("input.input_name").autocomplete({
					source: "switcher.php?need=autocomplete_names&e=" + EveningID + "&",
					minLength: 2,
				});
				$(".timepicker").datetimepicker({ datepicker: false, format: "H:i" });
			},
		});
	},
	setEveningData: function (target) {
		postAjax({
			data: {
				need: "apply_evening",
				eve_date: document.body.querySelector("input[name=eve_date]").value,
				eve_place: document.body.querySelector("input[name=eve_place]").value,
				eve_place_info: document.body.querySelector("input[name=eve_place_info]").value,
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
		postAjax({
			data: {
				need: "apply_evening",
				eve_date: document.body.querySelector("input[name=eve_date]").value,
				eve_place: document.body.querySelector("input[name=eve_place]").value,
				eve_place_info: document.body.querySelector("input[name=eve_place_info]").value,
				data: JSON.stringify(data),
			},
		});
	},
	eveningPlace: function (event) {
		postAjax({
			data: {
				need: "get_place_info",
				place: event.target.value,
			},
			successFunc: function (result) {
				document.body.querySelector('input[name="eve_place_info"]').value = result;
			},
		});
	},
	dischargeGamer: function (target) {
		if (confirm("Точно удалить игрока из записи?")) {
			postAjax({
				data: {
					need: "discharge_gamer",
					id: target.id.split("_")[0],
				},
				successFunc: function () {
					location.reload();
				},
			});
		}
	},
	clickCommonHandler: function (event) {
		let target = event.target;
		let datasetArray = Object.entries(target.dataset);
		if (datasetArray.length === 0) target = target.closest("a[data-form-type],a[data-action-type],div[data-form-type],div[data-action-type]");
		if (target === null) return false;
		
		if ("formType" in target.dataset) {
			event.preventDefault();
			let editTarget = target.dataset.editRow || target.dataset.editImage || target.dataset.editTarget || "";
			let data = { need: target.dataset.formType + "_form" };
			if (editTarget !== "") data["editTarget"] = editTarget;
			for (let [key, value] of Object.entries(target.dataset)) {
				if (!['editRow','editImage','editTarget','formType'].includes(key))
					data[key] = value;
			}
			if (debug) console.log(data);
			postAjax({
				data: data,
				successFunc: function (result) {
					result = JSON.parse(result);
					if (result["error"] != 0) {
						alert(result["html"]);
						return false;
					}
					let type = camelize(target.dataset.formType);
					if (debug) console.log(type);
					let [modalOverlay, modal] = modalEvent(result["html"], type);
					$(".modal-body input.input_name").autocomplete({
						source: "switcher.php?need=autocomplete_names",
						minLength: 2,
					});
					$(".modal-body .datepick").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });
					$(".modal-body .timepicker").datetimepicker({ datepicker: false, format: "H:i" });
					modal.querySelector("input").focus();
					modal.querySelector("form").addEventListener("submit", (submitEvent) => {
						submitEvent.preventDefault();
						actionHandler[type](modal);
					});
					if (result["javascript"]) window.eval(result["javascript"]);
					$(".modal-body textarea").cleditor({ height: 200});
				},
			});
		} else if ("action" in target.dataset) {
			event.preventDefault();
			postAjax({
				data: {
					need: target.dataset.action,
				},
				successFunc: function (result) {
					result = JSON.parse(result);
					if (result["error"] != 0) {
						alert(result["html"]);
						return false;
					}
					location.reload();
				},
			});
		} else if ("actionType" in target.dataset) {
			let type = camelize(target.dataset.actionType);
			if (debug) console.log(type);
			try {
				event.preventDefault();
				actionHandler[type](target, event);
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
	let form = createNewElement({
		tag: "form",
		method: "POST",
		action: url,
	});
	for (let name in data) {
		let input = createNewElement({
			tag: "input",
			type: "hidden",
			name: name,
			value: data[name],
		});
		form.appendChild(input);
	}
	document.body.appendChild(form);
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
	return [modalOverlay, modal];
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
	modalOverlay.id = divId;

	let modal = document.createElement("div");
	modal.className = "modal";

	let modalBody = document.createElement("div");
	modalBody.className = "modal-body";

	modal.append(modalBody);
	modalOverlay.append(modal);

	document.body.append(modalOverlay);
	modalOverlay.addEventListener("click", closeModalWindow);
	return [modalOverlay, modal, modalBody];
}
function closeModalWindow(event) {
	if (debug) console.log(event);
	if (!event.target.classList.contains("modal-close")) return;
	let modalOverlay = event.target.closest(".modal-overlay");
	let modalsAll = document.body.querySelectorAll(".modal-body");
	$(modalOverlay).animate(
		{ opacity: 0, top: "45%" },
		200, // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
		function () {
			// пoсле aнимaции
			modalOverlay.style.display = "none"; // делaем ему display: none;
			if (modalsAll.length === 1) $("#overlay").fadeOut(400); // скрывaем пoдлoжку
			modalOverlay.removeEventListener("click", closeModalWindow);
			modalOverlay.remove();
		}
	);
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
function postAjax({ data, successFunc, errorFunc }) {
	if (successFunc == undefined) {
		successFunc = function (result) {
			console.log("Not set `successFunc`. Ajax result: " + result);
			alert("Успешно!");
		};
	}
	if (errorFunc == undefined) {
		errorFunc = function (result) {
			console.log(`Error: Ошибка связи с сервером ${result}`);
			alert("Error: Ошибка связи с сервером");
		};
	}
	data = simpleObjectToGetString(data);
	if (debug) {
		console.log(data);
		successFunc = catchResult(successFunc);
		errorFunc = catchResult(errorFunc);
	}
	$.ajax({
		url: "switcher.php",
		type: "POST",
		data: data,
		success: successFunc,
		error: errorFunc,
	});
}

function simpleObjectToGetString(obj) {
	let strData = "";
	for (let item in obj) {
		strData += `${item}=${obj[item]}&`;
	}
	return strData.slice(0, -1);
}
function serializeForm(target) {
	let elements = target.querySelectorAll("input, select, textarea");
	let result = {};
	elements.forEach((element) => {
		if (element.tagName === "INPUT" && element.type === "checkbox" && !element.checked) return;
		result[element.name] = element.value;
	});
	return result;
}
function camelize(str) {
	return str
		.split("-") // разбивает 'my-long-word' на массив ['my', 'long', 'word']
		.map((word, index) => (index == 0 ? word : word[0].toUpperCase() + word.slice(1)))
		.join(""); // соединяет ['my', 'Long', 'Word'] в 'myLongWord'
}
function catchResult(func) {
	return function (args) {
		console.log(...args);
		return func.call(this, args);
	};
}
function clearBlock(block) {
	while (block.firstChild && block.removeChild(block.firstChild));
}
function createNewElement({ tag: tagName = "div", ...attributes }) {
	if (debug) console.log(attributes);
	let element = document.createElement(tagName);
	applyAttributes(element, attributes);
	return element;
}
function applyAttributes(element, attributes) {
	for (let [attName, attrValue] of Object.entries(attributes)) {
		if (typeof attrValue !== "object") element[attName] = attrValue;
		else applyAttributes(element[attName], attrValue);
	}
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
