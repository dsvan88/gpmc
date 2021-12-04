
actionHandler.userAvatarCropFormReady = function ({ modal, result }) {
	$('div.cropped-image-place>img').cropper({
		aspectRatio: 3.5 / 4,
		minContainerWidth: 325,
		minContainerHeight: 220,
		checkOrientation: false,
	});
}
actionHandler.userAvatarCropSubmit = function (event) {
	event.preventDefault();
	const img = event.target.querySelector('div.cropped-image-place>img');
	const formData = new FormData;
	formData.append('need', 'do_user-avatar-save-croped');
	formData.append('image', img.src);
	formData.append('uid', event.target.dataset.uid);
	formData.append('data', JSON.stringify($(img).data("cropper").getData(true)));
	postAjax({
		data: formData,
		successFunc: function (result) {
			if (result["error"] === 0) {
				event.target.querySelector('.modal-close').click();
				console.log(document.body.querySelector(`.modal form[data-uid="${event.target.dataset.uid}"] .profile__avatar-place`));
				const userAvatarPlace = document.body.querySelector(`.modal form[data-uid="${event.target.dataset.uid}"] .profile__avatar-place`);
				userAvatarPlace.innerHTML = result['html'];
				// window.location = window.location.origin
			} else alert(result["text"]);
		},
	});
}
actionHandler.userAvatarCrop = function ({target,event}) {
	let input = createNewElement({
		tag: 'input',
		type: 'file',
		accept: '.jpg, .jpeg, .png, .webp', 
		style: { display: 'none' }
	});
	document.body.append(input);
	
	input.click();
	let userId = target.closest('form').dataset.uid;

    input.onchange = function (newEvent) {
        const modal = new ModalWindow;
		let data = new FormData;
		data.append('img', input.files[0]);
		data.append('need', 'do_user-avatar-upload-raw');
		data.append('uid', userId);
		postAjax({
			data: data,
			successFunc: function (result) {
                if (result['error'] === 0) {
					actionHandler.commonFormEventEnd({ modal, data:result, formSubmitAction: 'userAvatarCropSubmit' });
					actionHandler.userAvatarCropFormReady({ modal, result});
				}
				else
					alert(result['html']);
			}
		})
	}
}
// actionHandler.saveCroppedAvatar = function (modal) {
// 	actionHandler.reCropAvatar(modal);
// }