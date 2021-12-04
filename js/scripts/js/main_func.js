let debug = false;
actionHandler = {
	inputCommonHandler: function (event) {
		let action = event.target.dataset.actionInput;
		if (action.startsWith('get-autocomplete-')) {
			if (event.target.value.length > 2 ) {
				const type = action.replace(/get-autocomplete-/, '');
				postAjax({
					data: `{"need":"get_autocomplete-${type}","term":"${event.target.value}"}`,
					successFunc: function (result) {
						if (result) {
							let options = [];
							const deleteOptions = [];
							for (var i = 0; i < event.target.list.options.length; i++) {
								options.push(event.target.list.options[i].value);
								if (!result['result'].includes(event.target.list.options[i].value))
									deleteOptions.push(i);
							}
							result['result'].map(item => {
								if (options.includes(item)) return;
								const option = document.createElement('option');
								option.value = item;
								event.target.list.appendChild(option);
							});
							deleteOptions.map(index => event.target.list.options[index].remove());
						}
					},
				});
			}
		}
	},
	changeCommonHandler: function (event) {
		const type = camelize(event.target.dataset.actionChange);
		if (debug) console.log(type);
		try {
			actionHandler[type](event.target, event);
		} catch (error) {
			alert(`Не существует метода для этого action-type: ${type}... или возникла ошибка. Сообщите администратору!\r\n${error.name}: ${error.message}`);
			console.log(error);
		}
		
	},
	clickCommonHandler: function (event) {
		let target = event.target;
		let datasetArray = Object.entries(target.dataset);
		if (datasetArray.length === 0) target = target.closest("*[data-double-click-action],*[data-action]");
		if (target === null) return false;
		if ("doubleClickAction" in target.dataset) {
			if (dblclick_func !== false) {
				clearTimeout(dblclick_func);
				dblclick_func = false;
				actionHandler.dblClickFunc({ target, event });
			}
			else {
				dblclick_func = setTimeout(() => {
					if (dblclick_func !== false) {
						clearTimeout(dblclick_func);
						dblclick_func = false;
						actionHandler.clickFunc({ target, event });
					};
				}, 200)
			}
		}
		else
			actionHandler.clickFunc({ target, event });
			
	},
	dblClickFunc: function ({ target, event }) {
		event.preventDefault();
		const action = camelize(target.dataset.doubleClickAction);
		if (debug) console.log(action);
		try {
			actionHandler[action]({ target, event });
		} catch (error) {
			alert(`Не существует метода для этого double-click-action: ${action}... или возникла ошибка. Сообщите администратору!\r\n${error.name}: ${error.message}`);
			console.log(error);
		}
	},
	clickFunc: function ({ target, event }){
		if (!("action" in target.dataset)) return false;
		event.preventDefault();
		if (target.dataset.action.endsWith('-form'))
			return this.formGetAction(target);

		const type = camelize(target.dataset.action);
		if (debug) console.log(type);
		if (actionHandler[type] != undefined) {
			try {
				actionHandler[type]({ target, event });
			} catch (error) {
				alert(`Не существует метода для этого action-type: ${type}... или возникла ошибка. Сообщите администратору!\r\n${error.name}: ${error.message}`);
				console.log(error);
			}
		}
		else {
			let action = target.dataset.action;
			if (action.startsWith('get-')) {
				action = action.replace(/^get-/, 'get_');
			}
			else {
				action = `do_${action}`;
			}
			const formData = new FormData;
			formData.append('need', action);
			for (let [key, value] of Object.entries(target.dataset)) {
				if (key !== 'action')
					formData.append(key, value);
			}
			console.log(formDataToJson(formData));
			postAjax({
				data: formDataToJson(formData),
				successFunc: function (result) {
					if (result["error"] != 0) {
						alert(result["text"] || result["html"]);
						return false;
					}
					location.reload();
				},
			});
		}
	},
	formGetAction: function (target) {
		
		const modal = this.commonFormEventStart();
		const editTarget = target.dataset.editRow || target.dataset.editImage || target.dataset.editTarget || "";
		const data = new FormData();
		data.append('need', "form_" + target.dataset.action.replace(/-form$/, ''));
		if (editTarget !== "")
			data.append("editTarget", editTarget);
		const defaultKeys = ['editRow', 'editImage', 'editTarget', 'action'];
		for (let [key, value] of Object.entries(target.dataset)) {
			if (!defaultKeys.includes(key))
				data.append(key, value);
		}

		postAjax({
			data: formDataToJson(data),
			successFunc: function (data) {
				if (data["error"] != 0) {
					actionHandler.commonFormEventEnd({ modal, data });
					return false;
				}
				const action = camelize(target.dataset.action);
				if (debug) console.log(action);

				actionHandler.commonFormEventEnd({ modal, data, formSubmitAction: action+'Submit' });
				
				// actionHandler.CommonFormReady({ modal, data, action });
				if (actionHandler[action + "FormReady"]) {
					actionHandler[action + "FormReady"]({ modal, data });
				}
			},
		});
		return true;
	},
	commonFormEventStart: function (event) {
        return new ModalWindow();
	},
	commonFormEventEnd: function ({modal, data, formSubmitAction, ...args}) {
        let modalWindow;
        if (data['error'] === 0)
            modalWindow = modal.fillModalContent(data);
        else
			modalWindow = modal.fillModalContent({ html: data['html'], title: 'Error!', buttons: [{ 'text': 'Okay', 'className': 'modal-close positive' }] });
		
		// modalWindow.querySelectorAll('*[data-action]').forEach(block => block.addEventListener('click', actionHandler.clickCommonHandler));

		if (data["jsFile"]) addScriptFile(data["jsFile"]);
		if (data["cssFile"]) addCssFile(data["cssFile"]);

		$(".modal-container .datepick").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });

        const form = modalWindow.querySelector('form');
        if (form !== null && actionHandler[formSubmitAction]) {
            form.addEventListener('submit', (event) => actionHandler[formSubmitAction](event, modal, args))
		}
		let textareas = modalWindow.querySelectorAll("textarea");
		if (textareas.length > 0) {
			addScriptFile('/js/ckeditor/ckeditor.js');
			let applyCKeditor = setInterval(() => {
				if (!CKEDITOR)
					return;
				let textareas = modalWindow.querySelectorAll("textarea");
				textareas.forEach((textarea) => {
					textarea.id = Math.random(321123);
					CKEDITOR.replace(textarea, {
						height: 300,
						filebrowserImageBrowseUrl: "js/kcfinder/browse.php?type=images",
						filebrowserImageUploadUrl: "js/kcfinder/upload.php?type=images",
					});
					CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
				
					if (textarea.classList.contains('news')) {
						CKEDITOR.on("instanceReady", function (event) {
							textarea.nextElementSibling.querySelector("a.cke_button__save").onclick = actionHandler.currentFormSubmit;
						});
					}
				});
				clearInterval(applyCKeditor);
			}, 100)
		}

		let autoCompleteInputs = modalWindow.querySelectorAll("*[data-autocomplete]");
		autoCompleteInputs.forEach(
			element => {
				let source = function (request, response) {
					postAjax({
						data: `{"need":"get_autocomplete-${element.dataset.autocomplete}","term":"${request.term}"}`,
						successFunc: function (result) {
							if (result)
								response(result['result']);
						},
					});
					// response();
				}
				$(element).autocomplete({
					source: source,
					minLength: 2
				});
			}
		);
	},
	participantFieldGet: function ({target}) {
		let newID = document.body.querySelectorAll(".booking__participant").length;
		postAjax({
			data: `{"need":"get_participant-field","id":"${newID}" }`,
			successFunc: function (result) {
				eveningGamersFields.insertAdjacentHTML("beforeend", result['html']);
				eveningGamersFields.querySelectorAll('input[data-action-change]').forEach(element =>
					element.addEventListener('change', (event) => actionHandler.changeCommonHandler.call(actionHandler, event))
				);
				eveningGamersFields.querySelectorAll('input[data-action-input]').forEach(element =>
					element.addEventListener('input', (event) => actionHandler.inputCommonHandler.call(actionHandler, event))
				);
				// let autoCompleteInputs = document.body.querySelectorAll("*[data-autocomplete]");
				// autoCompleteInputs.forEach(
				// 	element => {
				// 		let source = function (request, response) {
				// 			postAjax({
				// 				data: `{"need":"get_autocomplete-${element.dataset.autocomplete}","term":"${request.term}"}`,
				// 				successFunc: function (result) {
				// 					if (result)
				// 						response(result['result']);
				// 				},
				// 			});
				// 			// response();
				// 		}
				// 		$(element).autocomplete({
				// 			source: source,
				// 			minLength: 3
				// 		});
				// 	}
				// );
				// $(".timepicker").datetimepicker({ datepicker: false, format: "H:i" });
			},
		});
	},
	participantFieldRemove: function({target}) {
		const parent = target.closest('div');
		const nameInput = parent.querySelector('input[name="participant[]"]');
		const arriveInput = parent.querySelector('input[name="arrive[]"]');
		const durationInput = parent.querySelector('select[name="duration[]"]');
		if (nameInput.value !== '')
			nameInput.value = '';
		if (arriveInput.value !== '')
			arriveInput.value = '';
		if (durationInput.value != 0)
			durationInput.value = 0;
	},
	participantCheckChange: function ({target}) {
		const newName = target.value.trim();
		if (newName === '') return false;
		let participantsList = [];
		document.body.querySelectorAll("input[name='participant[]']").forEach(item => item.value !== '' && item !== target ? participantsList.push(item.value) : false);
		if (participantsList.includes(newName)) {
			alert('Гравець з таким іменем - вже зареєстрований на поточний вечір!');
			target.value = '';
		}
	},
	eveningPlace: function (event) {
		postAjax({
			data: `{"need":"get_place-info","place":"${event.target.value}"}`,
			successFunc: function (result) {
				if (result['result'])
					document.body.querySelector('input[name="eve_place_info"]').value = result['result'];
			},
		});
	},
	eveningPrepeare: function ({target}) {
		const form = target.closest('form');
		const formData = new FormData(form);
		formData.append('need', 'do_evening-approve');
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0) window.location = window.location.origin;
				else alert(result["txt"]);
			},
		});
	},
	eveningApprove: function ({target}) {
		const form = target.closest('form');
		const formData = new FormData(form);
		formData.append('need', 'do_evening-approve');
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0) window.location = window.location.origin;
				else alert(result["txt"]);
			},
		});
	},
	settingsEditFormSubmit: function (event, modal, args) {
		event.preventDefault();
		const formData = new FormData(event.target);
		formData.append("need", "do_settings-edit");
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0){
					alert(result["text"])
					window.location = window.location.origin;
				}
				else alert(result["text"]);
			},
		});
	},
	userSinginFormSubmit: function (event, modal, args) {
		event.preventDefault();
		const formData = new FormData(event.target);
		formData.append("need", "do_user-singin");
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0)
					window.location = window.location.origin;
				else alert(result["text"]);
			},
		});
	},
	userLogout: function () {
		postAjax({
			data: `{"need":"do_user-logout"}`,
			successFunc: function (result) {
				if (result["error"] == 0) window.location = window.location.origin;
				else alert(result["txt"]);
			},
		});
	},
	userRegisterFormSubmit: function (event, modal, args) {
		event.preventDefault();
		const formData = new FormData(event.target);
		if (formData.get("password") !== formData.get("chk_password")) {
			event.target.querySelector("input[name=password]").focus();
			alert("Пароли не співпадають!");
			return false;
		}
		formData.append('need','do_user-registration');
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0) {
					alert(result["text"]);
					modal.modal.querySelector('.modal-close').click();
				} else {
					alert(result["text"]);
					modal.modal.querySelector(`input[name=${result["wrong"]}]`).focus();
					
				}
			},
		});
	},
	userProfileFormSubmit: function (event, modal, args) {
		event.preventDefault();
		let formData = new FormData(event.target);
		formData.append('need', 'do_user-profile-edit');
		postAjax({
			data: formData,
			successFunc: function (result) {
				if (result["error"] === 0) {
					alert(result["text"]);
					event.target.querySelector('.modal-close').click();
				} else alert(result["text"]);
			},
		});
	},
	CommonFormReady: function ({ modal = null, result = {}, type = null}) {
		$(".modal-body input.input_name").autocomplete({
			source: "switcher.php?need=autocomplete_names",
			minLength: 2,
		});
		$(".modal-body .datepick").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });
		$(".modal-body .timepicker").datetimepicker({ datepicker: false, format: "H:i" });
		let firstInput = modal.querySelector("input");
		if (firstInput !== null) firstInput.focus();
		let form = modal.querySelector("form");
		if (form !== null) {
			form.addEventListener("submit", (submitEvent) => {
				submitEvent.preventDefault();
				actionHandler[type](modal);
			});
		}
		if (result["javascript"]) window.eval(result["javascript"]);
		$(".modal-body textarea").cleditor({ height: 200 });
	},
};
// ПЕРЕДЕЛАТЬ НА СВЯЗКУ <details><summary></summary></details>
// function open_log(id) {
// 	$("#Log_" + id).removeClass("hide");
// 	$("#ShowLog_" + id).text("- Скрыть лог игры");
// }
// function close_log(id) {
// 	$("#Log_" + id).addClass("hide");
// 	$("#ShowLog_" + id).text("+ Открыть лог игры");
// }
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

async function postAjax({ data, formData, successFunc, errorFunc, method = 'json', ...options }) {
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

	if (debug) {
		console.log(data);
		successFunc = catchResult(successFunc);
		errorFunc = catchResult(errorFunc);
	}
	try {
		// console.log(typeof data == 'string' ? 'application/json' : 'multipart/form-data');
		$options = {
			method: 'POST', // или 'PUT'
			body: data, // данные могут быть 'строкой' или {объектом}!
		}
		if (typeof data == 'string'){
			$options['headers'] = {
				'Content-Type': 'application/json'
			}
		}
		const response = await fetch('switcher.php', $options)
		// 	headers: {
		// 		'Content-Type': typeof data == 'string' ? 'application/json' : 'multipart/form-data'
		// 	}
		// });
		if (response.ok) {
			successFunc(await response[method]());
		}
		else {
			errorFunc(response.status);
		}
	} catch (error) {
		console.error('Ошибка:', error);
	}
}

function simpleObjectToFormData(obj) {
	let formData = new FormData();
	for (let item in obj)
		formData.append(item,obj[item]);
	return formData;

}
function simpleObjectToGetString(obj) {
	let strData = "";
	for (let item in obj) {
		strData += `${item}=${obj[item]}&`;
	}
	return strData.slice(0, -1);
}
function serializeForm(target) {
	
	const elements = target.querySelectorAll("input,select,textarea");
	let result = {};
	elements.forEach((element) => {
		if (element.tagName === "INPUT" && element.type === "checkbox" && !element.checked) return;
		if (element.tagName === "TEXTAREA" && element.name === "html" && element.id !== undefined && CKEDITOR.instances[element.id]){
			result[element.name] = CKEDITOR.instances[element.id].getData().replace(/\&/g, "%26");
			return;
		}
		if (element.value == '') return;
		if (result.hasOwnProperty(element.name)) {
			if (typeof result[element.name] === "string") {
				result[element.name] = [
					result[element.name],
					element.value.replace(/\&/g, "%26")
				]
			}
			else
				result[element.name][result[element.name].length] = element.value.replace(/\&/g, "%26");
			return;
		}
		result[element.name] = element.value.replace(/\&/g, "%26");
	});
	return result;
}
function camelize(str) {
	return str
		.split("-") // разбивает 'my-long-word' на массив ['my', 'long', 'word']
		.map((word, index) => (index == 0 ? word : word[0].toUpperCase() + word.slice(1)))
		.join(""); // соединяет ['my', 'Long', 'Word'] в 'myLongWord'
}

function addScriptFile(src) {
	if (Array.isArray(src)){
		for (let index = 0; index < src.length; index++) {
			addScriptFile(src[index])
		}
	}
	else{
		if (document.head.querySelector(`script[src="${src}"]`))
			return false;
		let script = document.createElement('script');
		script.src = src;
		script.async = true; // чтобы гарантировать порядок
		document.head.appendChild(script);
	}
}
function addCssFile(src) {
	if (Array.isArray(src)){
		for (let index = 0; index < src.length; index++) {
			addCssFile(src[index])
		}
	}
	else{
		if (document.head.querySelector(`link[href="${src}"]`))
			return false;
		let link  = document.createElement('link');
		link.rel  = 'stylesheet';
		link.type = 'text/css';
		link.href = src;
		link.media = 'all';
		document.head.appendChild(link);
	}
}

function formDataToJson(data) {
    const object = {};
    data.forEach((value, key) => {
        value = value.replace("'", '’');
        if (key.includes('[')) {
			key = key.substr(0, key.indexOf('['));
			if (!object[key])
				object[key] = [];
			object[key][object[key].length] = value;
			return;
        }
        else {
            object[key] = value;
        }
    });
    return JSON.stringify(object);
}

function catchResult(func) {
	return function (args) {
		console.log(args);
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
Array.prototype.shuffle = function () {
	let j;
	for (let i = this.length - 1; i > 0; i--) {
		j = Math.floor(Math.random() * (i + 1)); // случайный индекс от 0 до i
		[this[i], this[j]] = [this[j], this[i]];
	}
	return this;
};
