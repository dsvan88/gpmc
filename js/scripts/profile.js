actionHandler.editMyInfo = function (modal) {
	let data = serializeForm(modal);
	data["need"] = "edit-my-info";
	postAjax({
		data: data,
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] == 0) {
				alert(result["txt"]);
				location.reload();
			} else {
				alert(result["txt"]);
				modal.querySelector("input[name=" + result["wrong"] + "]").focus();
			}
		},
	});
};
actionHandler.editRow = function (target) {
	let div = target.closest("div");
	let elem = div.querySelector("#" + target.dataset.editRow);
	let textarea = document.createElement("textarea");
	textarea.innerHTML = elem.innerHTML;
	clearBlock(elem.innerHTML);
	elem.append(textarea);
	$(textarea).cleditor();
	div.querySelector(".info-row__apply").style.display = "inline";
	div.querySelector(".info-row__edit").style.display = "none";
};
actionHandler.saveRow = function (target) {
	let div = target.closest("div");
	let p = div.querySelector("p");
	let html = p.querySelector("textarea").value;
	postAjax({
		data: {
			need: "edit-my-info",
			column: target.dataset.saveRow,
			html: html.replace(/(\n|\t)/g, "").replace(/\&/g, "%26"),
		},
		successFunc: function (result) {
			result = JSON.parse(result);
			if (result["error"] == 0) {
				alert(result["txt"]);
				clearBlock(p.innerHTML);
				p.innerHTML = html;
				div.querySelector(".info-row__apply").style.display = "none";
				div.querySelector(".info-row__edit").style.display = "inline";
			} else {
				alert(result["txt"]);
			}
		},
	});
};
actionHandler.showCommentForm = function (target) {
	$("form#addComment").slideDown();
	$("form#addComment textarea").cleditor();
};
actionHandler.saveComment = function (target) {
	let html = target.closest("div").querySelector("textarea").value;
	postAjax({
		data: {
			need: "save-comment",
			type: "user",
			target: document.body.querySelector("div.profile").dataset.userId,
			html: html.replace(/(\n|\t)/g, "").replace(/\&/g, "%26"),
		},
		successFunc: function (result) {
			if (debug) console.log(result);
			result = JSON.parse(result);
			if (result["error"] === 0) {
				alert(result["txt"]);
				$("form#addComment").slideUp();
			} else alert(result["txt"]);
		},
	});
};
actionHandler.setVote = function (modal) {
	if (confirm('Вы уверены?')) {
		let data = serializeForm(modal);
		data['need'] = "set-vote";
		postAjax({
			data: data,
			successFunc: function (result) {
				if (debug) console.log(result);
				result = JSON.parse(result);
				if (result["error"] === 0) {
					alert(result["txt"]);
					$("form#addComment").slideUp();
				} else alert(result["txt"]);
			},
		});
	}
};
// actionHandler.reCropAvatar = function (target) {

// 	postAjax({
// 		data: data,
// 		successFunc: function (result) {
// 			if (debug) console.log(result);
// 			result = JSON.parse(result);
// 			if (result["error"] === 0) {
// 				alert(result["txt"]);
// 				$("form#addComment").slideUp();
// 			} else alert(result["txt"]);
// 		},
// 	});
// 	// $.ajax({
// 	// 	url: 'switcher.php'
// 	// 	, data: 'need=upload_file'
// 	// 	, type: 'POST'
// 	// 	, success: function (res) {
// 	// 		res = JSON.parse(res);
// 	// 		if (res['error'] === 0) {
// 	// 			AdditionalModalEvent(res['html'], res['size']);
// 	// 			make_cropper()
// 	// 		}
// 	// 		else
// 	// 			alert(res['html']);
// 	// 	}
// 	// 	, error: function (res) {
// 	// 		alert('Error: Ошибка связи с сервером');
// 	// 	}
// 	// });
// }
/* 
$("#MainBody").off("click", ".EditPencilTA");
$("#MainBody").on("click", ".EditPencilTA", function () {
	let id = $(this).attr("id");
	let html = $("span#" + id).html();
	$("span#" + id)
		.parent()
		.html('<textarea id="' + id + '">' + html + "</textarea>");
	$("textarea").cleditor();
	$(this).css({ display: "none" });
	$("a.ApplyTA#" + id).css({ display: "inline" });
	return false;
});
$("#MainBody").off("click", ".ApplyTA");
$("#MainBody").on("click", ".ApplyTA", function () {
	let id = $(this).attr("id");
	let html = $("textarea#" + id).val();
	$.ajax({
		url: "switcher.php",
		type: "POST",
		data: "need=edit_my_info&i=" + id + "&html=" + html,
		success: function (res) {
			let result = JSON.parse(res);
			if (result["error"]) alert(result["txt"]);
			else {
				$("textarea#" + id)
					.parent()
					.parent()
					.html('<span id="' + id + '">' + html + "</span>");
				$(".ApplyTA#" + id).css({ display: "none" });
				$("a.EditPencilTA#" + id).css({ display: "inline" });
				alert(result["txt"]);
			}
		},
	});
	return false;
}); */

// $(function () {
//     $('.datepick').datetimepicker({ format: 'd.m.Y H:i', dayOfWeekStart: 1 });
//     $('.timepicker').datetimepicker({ datepicker: false, format: 'H:i' });
//     $('#MainBody').off('click', '.EditPencil');
//     $('#MainBody').on('click', '.EditPencil', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=edit_my_info_form&c=' + $(this).attr('id')
//             , success: function (res) {
//                 ModalEvent(res, '420,250');
//                 $('.modal_window .datepick').datetimepicker({ timepicker: false, format: 'd.m.Y', dayOfWeekStart: 1 });
//             }
//         });
//         return false;
//     });
//     $('#MainBody').off('click', '.EditPencilTA');
//     $('#MainBody').on('click', '.EditPencilTA', function () {
//         let id = $(this).attr('id');
//         let html = $('span#' + id).html();
//         $('span#' + id).parent().html('<textarea id="' + id + '">' + html + '</textarea>')
//         $('textarea').cleditor();
//         $(this).css({ 'display': 'none' });
//         $('a.ApplyTA#' + id).css({ 'display': 'inline' });
//         return false;
//     });
//     $('#MainBody').off('click', '.ApplyTA');
//     $('#MainBody').on('click', '.ApplyTA', function () {
//         let id = $(this).attr('id');
//         let html = $('textarea#' + id).val();
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=edit_my_info&i=' + id + '&html=' + html
//             , success: function (res) {
//                 let result = JSON.parse(res);
//                 if (result['error'])
//                     alert(result['txt'])
//                 else {
//                     $('textarea#' + id).parent().parent().html('<span id="' + id + '">' + html + '</span>')
//                     $('.ApplyTA#' + id).css({ 'display': 'none' });
//                     $('a.EditPencilTA#' + id).css({ 'display': 'inline' });
//                     alert(result['txt']);
//                 }
//             }
//         });
//         return false;
//     });
//     $('body').off('click', '#EditUserInfoRow');
//     $('body').on('click', '#EditUserInfoRow', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=edit_my_info&' + $('#Form_EditUserInfoRow').serialize()
//             , success: function (res) {
//                 let result = JSON.parse(res);
//                 if (result['error'])
//                     alert(result['txt'])
//                 else {
//                     alert(result['txt']);
//                     $('#overlay').click();
//                 }
//             }
//         });
//         return false;
//     });
//     $('#MainBody').off('click', '.span_vote');
//     $('#MainBody').on('click', '.span_vote', function () {
//         let res = check_current_vote(uID, ($(this).hasClass('minus') ? 0 : 1), ($(this).hasClass('t_status') ? 1 : 0));
//         if (res['vote'] == 1)
//             AdditionalModalEvent(res['html'], '400,220');
//         else if (res['vote'] == 2)
//             AdditionalModalEvent(res['html'], '800,350');
//         else
//             AdditionalModalEvent(res['html'], '200,120');
//     });
//     $('body').off('click', '.span_button#MyVote');
//     $('body').on('click', '.span_button#MyVote', function () {
//         if (confirm('Вы уверены?')) {
//             $.ajax({
//                 url: 'switcher.php'
//                 , type: 'POST'
//                 , data: 'need=do_my_vote&m=' + $('input[name=motion]').val() + '&t=' + $('input[name=type]').val() + '&html=' + $('textarea[name=vote_comment]').val() + ($('input[name=v_id]').val() != undefined ? '&v=' + $('input[name=v_id]').val() : '') + ($('input[name=p_id]').val() != undefined ? '&p=' + $('input[name=p_id]').val() : '')
//                 , success: function (res) {
//                     let result = JSON.parse(res);
//                     if (result['error'])
//                         alert(result['txt'])
//                     else {
//                         alert(result['txt']);
//                         $('#overlay').click();
//                     }
//                 }
//             });
//         }
//     });
//     $('#MainBody').off('click', '#ApplyMyReg');
//     $('#MainBody').on('click', '#ApplyMyReg', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=my_record_form'
//             , success: function (res) {
//                 ModalEvent(res);
//                 $('.modal_window .timepicker').datetimepicker({ datepicker: false, format: 'H:i', allowTimes: ['17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00', '20:15', '20:30', '20:45', '21:00', '21:15', '21:30', '21:45', '22:00'], step: 15 });
//             }
//             , error: function (res) {
//                 alert('Error: Ошибка связи с сервером');
//             }
//         });
//         return false;
//     });
//     $('#MainBody').off('click', '#CancelMyReg');
//     $('#MainBody').on('click', '#CancelMyReg', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=cancel_my_reg'
//             , success: function (res) {
//                 let result = JSON.parse(res);
//                 if (result['error'] === 0) {
//                     alert(result['txt']);
//                     $('#overlay').click();
//                 }
//                 else
//                     alert(result['txt']);
//             }
//             , error: function (res) {
//                 alert('Error: Ошибка связи с сервером');
//             }
//         });
//         return false;
//     });
//     $('#MainBody').off('click', 'span#SaveComment');
//     $('#MainBody').on('click', 'span#SaveComment', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , type: 'POST'
//             , data: 'need=save_comment&t=user&u=' + uID + '&html=' + $('form#AddComment textarea').val()
//             , success: function (res) {
//                 res = JSON.parse(res);
//                 if (res['error'] === 0) {
//                     alert(res['txt']);
//                     $('form#AddComment').slideUp();
//                     $('span#SaveComment').attr('id', 'AddComment');
//                 }
//                 else
//                     alert(res['txt']);
//             }
//             , error: function (res) {
//                 alert('Error: Ошибка связи с сервером');
//             }
//         });
//     });
//     $('#Profile_PhotoDiv').off('click', '#Profile_PhotoPlace');
//     $('#Profile_PhotoDiv').on('click', '#Profile_PhotoPlace', function () {
//         if ($('#Profile_PhotoPlace img').hasClass('my_avatar')) {
//             $.ajax({
//                 url: 'switcher.php'
//                 , type: 'POST'
//                 , data: 'need=show_my_avatar'
//                 , success: function (res) {
//                     res = JSON.parse(res);
//                     if (res['error'] === 0) {
//                         AdditionalModalEvent(res['html'], res['size']);
//                     }
//                     else
//                         alert(res['txt']);
//                 }
//                 , error: function (res) {
//                     alert('Error: Ошибка связи с сервером');
//                 }
//             });
//         }
//         else if ($('#Profile_PhotoPlace img').hasClass('user_avatar')) {
//             $.ajax({
//                 url: 'switcher.php'
//                 , type: 'POST'
//                 , data: 'need=show_user_avatar&u=' + uID
//                 , success: function (res) {
//                     res = JSON.parse(res);
//                     if (res['error'] === 0) {
//                         AdditionalModalEvent(res['html'], res['size']);
//                     }
//                     else
//                         alert(res['txt']);
//                 }
//                 , error: function (res) {
//                     alert('Error: Ошибка связи с сервером');
//                 }
//             });
//         }
//     });
//     $('body').on('click', 'span#ReCropMyAvatar', function () {
//         $.ajax({
//             url: 'switcher.php'
//             , data: 'need=upload_file'
//             , type: 'POST'
//             , success: function (res) {
//                 res = JSON.parse(res);
//                 if (res['error'] === 0) {
//                     AdditionalModalEvent(res['html'], res['size']);
//                     make_cropper()
//                 }
//                 else
//                     alert(res['html']);
//             }
//             , error: function (res) {
//                 alert('Error: Ошибка связи с сервером');
//             }
//         });
//     })
//     $('body').on('click', 'span#CropMyNewAvatar', function () {
//         $('#Profile_PhotoDiv form input[type=file]').trigger('click');
//     })
//     $('#Profile_PhotoDiv').on('change', 'form input[type=file]', function () {
//         let fd = new FormData;
//         fd.append('img', $(this).prop('files')[0]);
//         fd.append('need', 'upload_file');
//         $.ajax({
//             url: 'switcher.php'
//             , data: fd
//             , processData: false
//             , contentType: false
//             , type: 'POST'
//             , success: function (res) {
//                 res = JSON.parse(res);
//                 if (res['error'] === 0) {
//                     AdditionalModalEvent(res['html'], res['size']);
//                     make_cropper()
//                 }
//                 else
//                     alert(res['html']);
//             }
//             , error: function (res) {
//                 alert('Error: Ошибка связи с сервером');
//             }
//         });
//     });
// });
