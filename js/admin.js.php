<?header('Content-Type: text/javascript');?>
var callBackReady= false;
$(function(){
	$('.datepick').datetimepicker({format:'d.m.Y H:i',dayOfWeekStart : 1});
	$('.timepicker').datetimepicker({datepicker:false,format:'H:i'});
	if ($('textarea').is('#editor'))
	{
		CKEDITOR.replace('editor',{
			height: 300,
			filebrowserBrowseUrl : 'js/kcfinder/browse.php?type=files',
			filebrowserImageBrowseUrl : 'js/kcfinder/browse.php?type=images',
			filebrowserUploadUrl : 'js/kcfinder/upload.php?type=files',
			filebrowserImageUploadUrl : 'js/kcfinder/upload.php?type=images'
		});
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	}
	$('#AP_Menu').on('click','ul', function(){
		$('#AP_Menu ul.active').removeClass('active');
		$(this).addClass('active');
	});
	$('#ImgTable').on('click','div.ImgPlace', function(){
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=get_setting_img&id='+$(this).attr('id')
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
					AdditionalModalEvent(res['html'],res['size']);
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	});
	$('body').on('click','span#SaveSettingData', function(){
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=edit_setting&id='+$('#EditSettingImg input[name="id"]').val()
				+'&n='+$('#EditSettingImg input[name="name"]').val()
				+'&u='+$('#EditSettingImg .ImgPlace img').attr('src')
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
				{
					alert(res['txt']);
					$('#overlay').click();
				}
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	});
	$('body').on('click','form#EditSettingImg div.ImgPlace', function(){
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=get_browser'
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
				{
					callBackReady = 'tmp_big_modal';
					AdditionalModalEvent(res['html'],res['size'],callBackReady);
				}
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	});
	$('#UsrTable').off('click','a.EditPencil');
	$('#UsrTable').on('click','a.EditPencil', function(){
		let prnt = $(this).parents('tr');
		let id = prnt.attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=edit_user_row_form&id='+id
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
					prnt.html(res['html']);
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	})
	$('#UsrTable').off('click','a.ApplyTA');
	$('#UsrTable').on('click','a.ApplyTA', function(){
		let prnt = $(this).parents('tr');
		let id = prnt.attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=edit_user_row&id='+id
				+'&name='+$('tr#'+id+' input[name="name"]').val()
				+'&fio='+$('tr#'+id+' input[name="fio"]').val()
				+'&status='+$('tr#'+id+' select[name="status"]').val()
				+'&rank='+$('tr#'+id+' select[name="rank"]').val()
				+'&birthday='+$('tr#'+id+' input[name="birthday"]').val()
				+'&gender='+$('tr#'+id+' select[name="gender"]').val()
				+'&email='+$('tr#'+id+' input[name="email"]').val()
				+'&ar='+($('tr#'+id+' input[name="ar"]:checkbox').is(':checked') ? '1' : '0')
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
					prnt.html(res['html']);
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	})
	$('#PntTable').off('click','a.EditPencil');
	$('#PntTable').on('click','a.EditPencil', function(){
		let prnt = $(this).parents('div.PntTableCell');
		let id = prnt.attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=edit_point_form&id='+id
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
					prnt.html(res['html']);
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	})
	$('#PntTable').off('click','a.ApplyTA');
	$('#PntTable').on('click','a.ApplyTA', function(){
		let prnt = $(this).parents('div.PntTableCell');
		let id = prnt.attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=edit_point&id='+id
				+'&name='+$('div.PntTableCell#'+id+' input[name="name"]').val()
				+'&value='+$('div.PntTableCell#'+id+' input[name="value"]').val()
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
					prnt.html(res['html']);
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
	})
	$('#TxtTable').off('click','a.EditPencil');
	$('#TxtTable').on('click','a.EditPencil', function(){
		let id = $(this).attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=get_setting_txt&id='+$(this).attr('id')
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']===0)
				{
					$('div#'+id+'.TxtCellContent').html(res['html']);
					if ($('form#EditSettingTxt textarea').is('#editor'))
					{
						CKEDITOR.replace('editor',{
							height: 200,
							filebrowserImageBrowseUrl : 'js/kcfinder/browse.php?type=images',
							filebrowserImageUploadUrl : 'js/kcfinder/upload.php?type=images',
						});
						CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
						CKEDITOR.on('instanceReady', function(event){
							$('a.cke_button__save').attr('onclick','');
							$('a.cke_button__save').on('click', function(event){
								$.ajax({
									url:'switcher.php'
									, type:'POST'
									, data:'need=edit_setting&id='+$('#EditSettingTxt input[name="id"]').val()
										+'&n='+$('#EditSettingTxt input[name="name"]').val()
										+'&html='+CKEDITOR.instances.editor.getData().replace(/\&/g,'%26')
									, success: function(res) {
										res = JSON.parse(res);
										if (res['error']===0)
										{
											$('div#'+id+'.TxtCellContent').html(CKEDITOR.instances.editor.getData());
											alert(res['txt']);
										}
										else
											alert(res['txt']);
									}
									, error: function(res) {
										alert('Error: Ошибка связи с сервером');
									}
								})
								return false;
							});
						});
					}
				}
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		})
		return false;
	});
});
window.callback = function(url) {
	if (callBackReady === false) return false;
	$('#EditSettingImg .ImgPlace img').attr('src',url);
	if ($('div').is('#'+callBackReady) && $('div#'+callBackReady).css('display') === 'block')
		$('div#'+callBackReady).animate({opacity: 0, top: '45%'},200,'swing',function(){
			$('div#'+callBackReady).remove();
			callBackReady = false;
		});
}