class ModalWindow {

	modal = null;
	modalIndex = null;
	commonOverlay = null;
	currentOverlay = null;
	headerTitle = null;

	constructor({ divId = "modalWindow", html = "", title = "", buttons = [] } = {}) {
		this.commonOverlay = document.body.querySelector("#overlay");
		if (this.commonOverlay === null) {
			this.commonOverlay = document.createElement("div");
			this.commonOverlay.id = "overlay";
			document.body.append(this.commonOverlay);
			setTimeout(() => this.commonOverlay.style.opacity = 0.3, 100);
		}
		this.prepeareModalWindow(divId);
		if (html !== '') {
			this.fillModalContent({ html, title, buttons });
		}
	};
	fillModalContent({ html = "", title = "", buttons = [] }) {
		let modalContainer = null;
		if (html !== "" || buttons.length !== 0) {
			modalContainer = this.modal.querySelector('.modal-container');
			modalContainer.innerHTML = html;
		}
		if (title !== ""){
			this.modal.querySelector('.modal-title').innerText = title;
		}
		if (buttons.length !== 0) {
			modalContainer = modalContainer || this.modal.querySelector('.modal-container');
			const modalButtons = document.createElement('div');
			modalButtons.className = 'modal-buttons';
			buttons.forEach(button => {
				const element = document.createElement('button');
				element.innerText = button.text;
				element.className = button.className;
				modalButtons.append(element);
				
			});
			modalContainer.append(modalButtons)
		}
		return this.modal;
	};
	prepeareModalWindow(divId = "modalWindow") {
		let modalHeader = document.createElement("div");
		modalHeader.className = "modal__header";
		this.headerTitle = document.createElement("h3");
		this.headerTitle.className = 'modal-title';
		let modalClose = document.createElement("i");
		modalClose.className = "fa fa-window-close modal-close";
		modalHeader.append(document.createElement("i"));
		modalHeader.append(this.headerTitle);
		modalHeader.append(modalClose);

		this.modal = document.createElement("div");
		this.modal.className = "modal";
		this.modal.append(modalHeader);
		this.modal.innerHTML += `
			<div class="modal-container">
				<div>
					<i class="fa fa-cog fa-spin fa-3x fa-fw" ></i>
					<span class="sr-only">Загрузка...</span>
				</div>
			</div>`;

		this.currentOverlay = document.createElement("div");
		this.currentOverlay.className = "modal-overlay modal-close";
		this.currentOverlay.id = divId;
		this.currentOverlay.append(this.modal);

		document.body.append(this.currentOverlay);
		const _self = this;
		this.currentOverlay.addEventListener("click",  (event) => _self.closeModalWindow(event));

		this.popUpModal();
	};
	popUpModal() {
		let allModals = document.body.querySelectorAll(".modal");
		this.modalIndex = allModals.length;

		this.currentOverlay.style.zIndex = 4 + this.modalIndex;
		this.modal.style.opacity = 0;
		this.modal.style.display = 'block';
		setTimeout(() => {
			this.modal.style.opacity = 1;
			this.modal.style.transform = 'translateY(-2%)';
		}, 100);

	};
	closeModalWindow(event) {
		if (!event.target.classList.contains("modal-close")){
			return;
		}
		if (this.currentOverlay === event.target && !confirm('Ви впевнені, що бажаєте закрити поточне вікно?')){
			return;
		}
		if (event.target.classList.contains("modal-reload-page")) {
			window.location = window.location.href;
			return;
		}

		if (this.modalIndex === 1) {
			this.commonOverlay.style.opacity = 0;
			setTimeout(() => this.commonOverlay.remove(), 300);
		}
		const _self = this;
		this.currentOverlay.removeEventListener("click", (event) => _self.closeModalWindow(event));

		this.modal.style.opacity = 0;
		this.modal.style.transform = 'translateY(2%)';
		setTimeout(() => this.currentOverlay.remove(), 300);

		if (event.target.classList.contains("modal-reload-page")) {
			window.location = window.location.href;
		}
	}
}