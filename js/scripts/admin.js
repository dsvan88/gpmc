var callBackReady = false;

let textareas = document.body.querySelectorAll("textarea");
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
			textarea.nextElementSibling.querySelector("a.cke_button__save").onclick = actionHandler.applyNews;
		// form.querySelector("a.cke_button__save").onclick = actionHandler.applySetting;
		});
	}
		
});

actionHandler.toggleListItems = function (target) {
	target.parentElement.querySelectorAll("li").forEach((item) => $(item).slideToggle("fast"));
};
actionHandler.applySetting = function (event) {
	target = event.target || event;
	let mainBlock = target.closest("div.texts-table__cell");
	let newHTML = CKEDITOR.instances[mainBlock.querySelector("textarea").id].getData();
	postAjax({
		data: {
			need: "apply-setting",
			id: mainBlock.querySelector("a.apply-setting").dataset.textId,
			html: newHTML.replace(/\&/g, "%26"),
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				alert(result["txt"]);
				mainBlock.children[1].innerHTML = newHTML;
				mainBlock.children[2].remove();
				[...mainBlock.children].forEach((child) => (child.style.display = "block"));
			} else alert(result["txt"]);
		},
	});
};
actionHandler.editSettingText = function (target) {
	let textId = target.dataset.textId;
	let mainBlock = target.closest("div.texts-table__cell");
	let form = createNewElement({
		tag: "form",
		method: "POST",
		action: "switcher.php?need=apply-setting&id=" + textId,
	});

	let header3 = createNewElement({
		tag: "h3",
		textContent: mainBlock.querySelector("h3.texts-table__cell__title").firstChild.textContent.replace(/(\n|\t)*/g, ""),
	});
	let anchor = createNewElement({
		tag: "a",
		className: "apply-setting",
		dataset: {
			actionType: "apply-setting",
			textId: textId,
		},
	});
	let imageApply = createNewElement({
		tag: "img",
		title: "Зберегти",
		alt: "Зберегти",
		src: "/css/images/apply.png",
	});
	anchor.append(imageApply);
	header3.append(anchor);
	form.append(header3);

	let textarea = document.createElement("textarea");
	textarea.id = new Date().getTime();
	textarea.innerHTML = mainBlock.querySelector("div.texts-table__cell__content").innerHTML;
	form.append(textarea);

	CKEDITOR.replace(textarea, {
		height: 200,
		filebrowserImageBrowseUrl: "js/kcfinder/browse.php?type=images",
		filebrowserImageUploadUrl: "js/kcfinder/upload.php?type=images",
	});
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;

	[...mainBlock.children].forEach((child) => (child.style.display = "none"));
	mainBlock.append(form);

	CKEDITOR.on("instanceReady", function (event) {
		form.querySelector("a.cke_button__save").onclick = actionHandler.applySetting;
	});
};
actionHandler.editSettingsImage = function (modal) {
	postAjax({
		data: {
			need: "apply-setting",
			id: modal.querySelector('input[name="id"]').value,
			name: modal.querySelector('input[name="name"]').value,
			value: modal.querySelector("div.image-place img").src,
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				alert(result["txt"]);
				closeModalWindow({ target: modal.parentElement });
			} else alert(result["txt"]);
		},
	});
};
actionHandler.editUserRow = function (target) {
	let prnt = target.closest("tr");
	postAjax({
		data: {
			need: "edit-user-row",
			id: prnt.dataset.userId,
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				prnt.innerHTML = result["html"];
				$("input[name=birthday]").datetimepicker({ timepicker: false, format: "d.m.Y", dayOfWeekStart: 1 });
			} else alert(result["txt"]);
		},
	});
};
actionHandler.editPoints = function (target) {
	postAjax({
		data: {
			need: "edit-points",
			id: target.dataset.pointsId,
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				clearBlock(target);
				target.innerHTML = result["html"];
			} else alert(result["txt"]);
		},
	});
};
actionHandler.applyNewPoints = function (target) {
	let parent = target.closest("div");
	let data = serializeForm(parent);
	data["need"] = target.dataset.actionType;
	data["id"] = parent.dataset.pointsId;
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				clearBlock(parent);
				parent.innerHTML = result["html"];
			} else alert(result["txt"]);
		},
	});
};
actionHandler.applyUserData = function (target) {
	let parent = target.closest("tr");
	let data = serializeForm(parent);
	data['need'] = 'apply-user-data';
	data['id'] = parent.dataset.userId;
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) parent.innerHTML = result["html"];
			else alert(result["txt"]);
		},
	});
};
actionHandler.applyNews = function (target) {
	target = target.target || target;
	let parent = target.target || target.closest('div[data-action-mode]');
	let data = serializeForm(parent);
	data['need'] = 'apply-news';
	if (parent.dataset.newsId) data['id'] = parent.dataset.newsId;
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) location.reload();
			else alert(result["txt"]);
		},
	});
};
actionHandler.showAddNewsForm = function (target) {
	target.style.display = "none";
	target.nextElementSibling.style.display = 'inline';
	let addNewsDiv = document.body.querySelector('div.add-news__content');
	$(addNewsDiv).slideToggle("fast");
	// Придумать, куда можно это переместить для большей универсальности и удобства
	addNewsDiv.querySelector("a.cke_button__save").onclick = actionHandler.applyNews;
};
window.callBackForKCFinderBrowser = function (url) {
	if (callBackReady === false) return false;
	let image = document.body.querySelector("div#editSettingsImage div.image-place img");
	image.src = url;
	image.onload = function () {
		if (image.offsetHeight > image.closest("div").offsetHeight) {
			[image.style.height, image.style.width] = [image.style.width, image.style.height];
		}
		closeModalWindow({ target: document.body.querySelector("div#getKcfinderBrowser") });
	};
};
