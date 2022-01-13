let dblclick_func = false;
// console.log(window.location);
document.body.addEventListener('click', actionHandler.clickCommonHandler);

document.body.querySelectorAll('input[data-action-input]').forEach(element =>
	element.addEventListener('input', (event) => actionHandler.inputCommonHandler.call(actionHandler, event))
);
document.body.querySelectorAll('input[data-action-change]').forEach(element =>
	element.addEventListener('change', (event) => actionHandler.changeCommonHandler.call(actionHandler, event))
);

let menuCheckbox = document.body.querySelector('#profile-menu-checkbox');
if (menuCheckbox) {
	let menu = document.body.querySelector('div.header__profile-options');
	document.body.addEventListener('click', (event) => {
		if (!menuCheckbox.checked) {
			return false;
		};

		if (!(event.target == menu || menu.contains(event.target))) {
			menuCheckbox.checked = false;
		}
	});
};

$(function () {
	$('.datepick').datetimepicker({ format: 'd.m.Y H:i', dayOfWeekStart: 1 });
	$('.timepicker').datetimepicker({ datepicker: false, format: 'H:i' });
	let autoCompleteInputs = document.body.querySelectorAll("*[data-autocomplete]");
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
})