actionHandler.applyMyBooking = function (modal) {
	let data = serializeForm(modal);
	data["need"] = "apply-my-booking";
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				alert(result["txt"]);
				location.reload();
			} else alert(result["txt"]);
		},
	});
};
actionHandler.cancelMyBooking = function (target) {
	postAjax({
		data: {
			need: "cancel-my-booking",
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] === 0) {
				alert(result["txt"]);
				location.reload();
			} else alert(result["txt"]);
		},
	});
};
