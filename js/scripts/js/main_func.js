let debug = false;
actionHandler = {
	inputCommonHandler: function (event) {
		let action = event.target.dataset.actionInput;
		if (action.startsWith('get-autocomplete-')) {
			if (event.target.value.length > 2) {
				const type = action.replace(/get-autocomplete-/, '');
				postAjax({
					data: `{"need":"get_autocomplete-${type}","term":"${event.target.value}"}`,
					successFunc: function (result) {
						if (result) {
							let options = [];
							const deleteOptions = [];
							for (let i = 0; i < event.target.list.options.length; i++) {
								options.push(event.target.list.options[i].value);
								if (!result['result'].includes(event.target.list.options[i].value)){
									deleteOptions.push(i);
								}
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
			actionHandler[type](event);
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
		else{
			actionHandler.clickFunc({ target, event });
		}
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
	clickFunc: function ({ target, event }) {
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
			if (!defaultKeys.includes(key)){
				data.append(key, value);
			}
		}

		postAjax({
			data: formDataToJson(data),
			successFunc: function (data) {
				if (data["error"] != 0) {
					actionHandler.commonFormEventEnd({ modal, data });
					return false;
				};
				const action = camelize(target.dataset.action);
				if (debug) {
					console.log(action);
				};
				actionHandler.commonFormEventEnd({ modal, data, formSubmitAction: action + 'Submit' });

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
	commonFormEventEnd: function ({ modal, data, formSubmitAction, ...args }) {
		let modalWindow;
		if (data['error'] === 0){
			modalWindow = modal.fillModalContent(data);
		}else{
			modalWindow = modal.fillModalContent({ html: data['html'], title: 'Error!', buttons: [{ 'text': 'Okay', 'className': 'modal-close positive' }] });
		};

		if (data["jsFile"]) {
			addScriptFile(data["jsFile"]);
		};
		if (data["cssFile"]) {
			addCssFile(data["cssFile"]);
		};

		$(".modal-container .datepick").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });

		const form = modalWindow.querySelector('form');
		if (form !== null && actionHandler[formSubmitAction]) {
			form.addEventListener('submit', (event) => actionHandler[formSubmitAction](event, modal, args))
		}
		let editors = modalWindow.querySelectorAll("div.editor-block");
		if (editors.length > 0) {
			if (!addScriptFile('/js/ckeditor.js', () => CKEditorApply(editors))) {
				CKEditorApply(editors);
			}
		}

		let autoCompleteInputs = modalWindow.querySelectorAll("*[data-autocomplete]");
		autoCompleteInputs.forEach(
			element => {
				let source = function (request, response) {
					postAjax({
						data: `{"need":"get_autocomplete-${element.dataset.autocomplete}","term":"${request.term}"}`,
						successFunc: function (result) {
							if (result) {
								response(result['result']);
							}
						},
					});
				};
				$(element).autocomplete({
					source: source,
					minLength: 2
				});
			}
		);
	},
	weekDayEdit: function ({ target }) {
		window.location = `/?weekid=${target.dataset.week}&dayid=${target.dataset.day}`;
	},
	dayApprove: function ({ target }) {
		const form = target.closest('form');
		const formData = new FormData(form);
		formData.append('need', 'do_day-approve');
		formData.append('weekId', form.dataset.wid);
		formData.append('dayId', form.dataset.did);
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0) {
					alert(result["message"]);
					if (form.dataset.wid != result['weekId'])
						window.location = `${window.location.origin}/?weekid=${result['weekId']}&dayid=${form.dataset.did}`;
				} else {
					alert(result["message"]);
				}
			},
		});
	},
	userDelete: function ({ target }) { 
		if (!confirm('Вы уверены, что хотите удалить игрока?')) return false;
		if (!confirm('Это так же удалит всю его историю игр. Опять таки... вы уверены?')) return false;
		postAjax({
			data: `{"need":"do_user-delete","uid":"${target.dataset.userId}"}`,
			successFunc: function (result) {
				alert(result["message"]);
			},
		});
	},
	participantFieldGet: function ({ target }) {
		const form = target.closest('form');
		const newID = form.querySelectorAll(".booking__participant").length;
		const participantsFields = form.querySelector(".booking__participants");
		postAjax({
			data: `{"need":"get_participant-field","id":"${newID}" }`,
			successFunc: function (result) {
				participantsFields.insertAdjacentHTML("beforeend", result['html']);
				participantsFields.querySelectorAll('input[data-action-change]').forEach(element =>
					element.addEventListener('change', (event) => actionHandler.changeCommonHandler.call(actionHandler, event))
				);
				participantsFields.querySelectorAll('input[data-action-input]').forEach(element =>
					element.addEventListener('input', (event) => actionHandler.inputCommonHandler.call(actionHandler, event))
				);
			},
		});
	},
	participantFieldRemove: function ({ target }) {
		const parent = target.closest('div');
		const nameInput = parent.querySelector('input[name="participant[]"]');
		const arriveInput = parent.querySelector('input[name="arrive[]"]');
		const durationInput = parent.querySelector('select[name="duration[]"]');
		if (nameInput.value !== ''){
			nameInput.value = '';
		}
		if (arriveInput.value !== ''){
			arriveInput.value = '';
		}
		if (durationInput.value != 0){
			durationInput.value = 0;
		}
	},
	participantCheckChange: function ({ target }) {
		const newName = target.value.trim();
		if (newName === '') {
			return false;
		}

		let participantsList = [];
		target.closest('form').querySelectorAll("input[name='participant[]']").forEach(item => item.value !== '' && item !== target ? participantsList.push(item.value) : false);
		if (newName !== '+1' && participantsList.includes(newName)) {
			alert('Гравець з таким іменем - вже зареєстрований на поточний вечір!');
			target.value = '';
		}
	},
	eveningPlace: function (event) {
		postAjax({
			data: `{"need":"get_place-info","place":"${event.target.value}"}`,
			successFunc: function (result) {
				if (result['result']){
					event.target.closest('form').querySelector('input[name="eve_place_info"]').value = result['result'];
				}
			},
		});
	},
	eveningPrepeare: function ({ target }) {
		const form = target.closest('form');
		const formData = new FormData(form);
		formData.append('need', 'do_evening-approve');
		formData.append('eid', form.dataset.eid);
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0){
					window.location = window.location.href;
				}
				else {
					alert(result["txt"]);
				}
			},
		});
	},
	eveningApprove: function ({ target }) {
		const form = target.closest('form');
		const formData = new FormData(form);
		formData.append('need', 'do_evening-approve');
		formData.append('eid', form.dataset.eid);
		postAjax({
			data: formDataToJson(formData),
			successFunc: function (result) {
				if (result["error"] == 0){
					window.location = window.location.href;
				} else {
					alert(result["txt"]);
				}
			},
		});
	},
	eveningAddBookingTable: function ({ target }) {
		const targetParent = target.closest('.booking__additional-evening');
		postAjax({
			data: `{"need":"get_evening-add-booking-table"}`,
			successFunc: function (result) {
				if (result['error'] == 0){
					targetParent.insertAdjacentHTML('beforebegin', result['html']);
					targetParent.previousElementSibling.previousElementSibling.querySelectorAll('input[data-action-input]').forEach(element =>
						element.addEventListener('input', (event) => actionHandler.inputCommonHandler.call(actionHandler, event))
					);
					targetParent.previousElementSibling.previousElementSibling.querySelectorAll('input[data-action-change]').forEach(element => {
							console.log(element);
							element.addEventListener('change', (event) => actionHandler.changeCommonHandler.call(actionHandler, event))
						}
					);
					$('.datepick').datetimepicker({ format: 'd.m.Y H:i', dayOfWeekStart: 1 });
				}
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
					alert(result["text"]);
					window.location = window.location.origin;
				}
				else {
					alert(result["text"]);
				}
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
				if (result["error"] == 0){
					window.location = window.location.origin;
				}else {
					alert(result["text"]);
				}
			},
		});
	},
	userLogout: function () {
		postAjax({
			data: `{"need":"do_user-logout"}`,
			successFunc: function (result) {
				if (result["error"] == 0) {
					window.location = window.location.origin;
				}
				else {
					alert(result["txt"]);
				}
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
				} else {
					alert(result["text"]);
				}
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
		if (firstInput !== null) {
			firstInput.focus();
		}
		let form = modal.querySelector("form");
		if (form !== null) {
			form.addEventListener("submit", (submitEvent) => {
				submitEvent.preventDefault();
				actionHandler[type](modal);
			});
		}
		if (result["javascript"]) {
			window.eval(result["javascript"]);
		}
		$(".modal-body textarea").cleditor({ height: 200 });
	},
};
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
		};
		if (typeof data == 'string'){
			$options['headers'] = {
				'Content-Type': 'application/json'
			}
		};
		const response = await fetch('switcher.php', $options);
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
	for (let item in obj){
		formData.append(item, obj[item]);
	}
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
		if (element.tagName === "INPUT" && element.type === "checkbox" && !element.checked) {
			return;
		};
		/* if (element.tagName === "TEXTAREA" && element.name === "html" && element.id !== undefined && CKEDITOR.instances[element.id]){
			result[element.name] = CKEDITOR.instances[element.id].getData().replace(/\&/g, "%26");
			return;
		}; */
		if (element.value == '') {
			return;
		};
		if (result.hasOwnProperty(element.name)) {
			if (typeof result[element.name] === "string") {
				result[element.name] = [
					result[element.name],
					element.value.replace(/\&/g, "%26")
				]
			}
			else{
				result[element.name][result[element.name].length] = element.value.replace(/\&/g, "%26");
			}
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

function addScriptFile(src,callback = '') {
	if (Array.isArray(src)){
		for (let index = 0; index < src.length; index++) {
			addScriptFile(src[index], callback)
		}
	}
	else{
		if (document.head.querySelector(`script[src="${src}"]`)){
			return false;
		}
		let script = document.createElement('script');
		script.src = src;
		script.async = true;
		document.head.appendChild(script);
		if (callback !== '')
			script.onload = callback;
		return true;
	}
}
function addCssFile(src) {
	if (Array.isArray(src)){
		for (let index = 0; index < src.length; index++) {
			addCssFile(src[index])
		}
	}
	else{
		if (document.head.querySelector(`link[href="${src}"]`)){
			return false;
		}
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
			if (!object[key]){
				object[key] = [];
			}
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
	if (debug) {
		console.log(attributes);
	}
	let element = document.createElement(tagName);
	applyAttributes(element, attributes);
	return element;
}
function applyAttributes(element, attributes) {
	for (let [attName, attrValue] of Object.entries(attributes)) {
		if (typeof attrValue !== "object") {
			element[attName] = attrValue;
		}
		else {
			applyAttributes(element[attName], attrValue);
		}
	}
}
function CKEditorApply(editors) {
	for (let index = 0; index < editors.length; index++) {
		let randomIndex = Math.random(321123);
		editors[index].id = randomIndex;
		DecoupledEditor.create(
			editors[index].querySelector('.editor')
		).then(
			editor => {
				const toolbarContainer = editors[index].querySelector('.toolbar-container');
				toolbarContainer.prepend(editor.ui.view.toolbar.element);
				if (!window.CKEDITOR) {
					window.CKEDITOR = {
						'instances' : {}
					};
				}
				window.CKEDITOR.instances[randomIndex] = editor;
			}
		)
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
