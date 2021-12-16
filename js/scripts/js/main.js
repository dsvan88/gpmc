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
if (menuCheckbox){
	document.body.addEventListener('mousemove', (event) => {

		if (!menuCheckbox.checked) {
			return false;
		};
		if (document.documentElement.clientWidth - event.clientX > document.documentElement.clientWidth / 3
			|| document.documentElement.clientHeight - event.clientY < document.documentElement.clientHeight / 2.5){
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