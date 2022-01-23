actionHandler.currentFormSubmit = function (event) {
    let form = event.target;
    if (form.tagName !== 'FORM') {
        form = form.closest('form');
    };
    form.submit();
};

actionHandler.newsCreateNewFormSubmit = function (event) {
    event.preventDefault();
    let form = event.target;
    if (form.tagName !== 'FORM') {
        form = form.closest('form');
    };
    let formData = new FormData(form);
    let newHTML = CKEDITOR.instances[form.querySelector("div.editor-block").id].getData();
    formData.append('html', newHTML);
    formData.append("need", "do_news-create-new");
    postAjax({
        data: formData,
        successFunc: function (result) {
            if (result["error"] == 0) {
                alert(result["message"]);
                window.location = window.location.href;
            } else {
                alert(result["message"]);
            }
        },
    });
};
actionHandler.newsEditFormSubmit = function (event) {
    event.preventDefault();
    let form = event.target;
    if (form.tagName !== 'FORM') {
        form = form.closest('form');
    };
    let formData = new FormData(form);
    let newHTML = CKEDITOR.instances[form.querySelector("div.editor-block").id].getData();
    formData.append('html', newHTML);
    formData.append("need", "do_news-edit");
    postAjax({
        data: formData,
        successFunc: function (result) {
            if (result["error"] == 0) {
                alert(result["message"]);
                window.location = window.location.href;
            } else {
                alert(result["message"]);
            }
        },
    });
};
actionHandler.settingTextEditFormSubmit = function (event) {
    event.preventDefault();
    let form = event.target;
    if (form.tagName !== 'FORM'){
        form = form.closest('form');
    }
    let formData = new FormData(form);
    let newHTML = CKEDITOR.instances[form.querySelector("div.editor-block").id].getData();
    formData.append('html', newHTML);
    formData.append("need", "do_setting-text-edit");
    postAjax({
        data: formData,
        successFunc: function (result) {
            if (result["error"] == 0) {
                window.location = window.location.origin;
            } else {
                alert(result["text"]);
            }
        },
    });
};