let dblclick_func = false;
document.body.addEventListener('click', actionHandler.clickCommonHandler);

document.body.querySelectorAll('input[data-action-input]').forEach(element =>
	element.addEventListener('input', (event) => actionHandler.inputCommonHandler.call(actionHandler,event))
)
document.body.querySelectorAll('input[data-action-change]').forEach(element =>
	element.addEventListener('change', (event) => actionHandler.changeCommonHandler.call(actionHandler,event))
)

if (typeof eve_place != "undefined") {
	eve_place.onchange = actionHandler.eveningPlace;
	console.log(eve_place);
}
let menuCheckbox = document.body.querySelector('#profile-menu-checkbox');
if (menuCheckbox){
	document.body.addEventListener('mousemove', (event) => {

		if (menuCheckbox.checked){
			if (document.documentElement.clientWidth - event.clientX > document.documentElement.clientWidth / 3
				|| document.documentElement.clientHeight - event.clientY < document.documentElement.clientHeight / 2)
				menuCheckbox.checked = false;
		}
	});
}

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
						if (result)
							response(result['result']);
					},
				});
				// response();
			}
			$(element).autocomplete({
				source: source,
				minLength: 2
			});
		}
	);

	// $('input.input_name').autocomplete({
	// 	source: 'switcher.php?need=autocomplete_names&',
	// 	minLength: 2
	// });
	// $('input[name="eve_place"]').autocomplete({
	// 	source: 'switcher.php?need=autocomplete_places&',
	// 	minLength: 2
	// });
})

// 	$('#MainHeader').on('click','a#aProfile,a#MainLogo', function(e){
// 		e = e || event;
// 		if (e.ctrlKey)
// 		{
// 			became_admin();
// 			return false;
// 		}
// 	});
// 	$('#MainBody').off('click','#ApplyMyReg');
// 	$('#MainBody').on('click','#ApplyMyReg', function(){
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=my_record_form'
// 			, success: function(res) {
// 				ModalEvent(res);
// 				$('.modal_window .timepicker').datetimepicker({datepicker:false,format:'H:i',allowTimes:['17:00','17:15','17:30','17:45','18:00','18:15','18:30','18:45','19:00','19:15','19:30','19:45','20:00','20:15','20:30','20:45','21:00','21:15','21:30','21:45','22:00'],step:15});
// 			}
// 			, error: function(res) {
// 				alert('Error: Ошибка связи с сервером');
// 			}
// 		});
// 		return false;
// 	});
// 	$('#MainBody').off('click','#CancelMyReg');
// 	$('#MainBody').on('click','#CancelMyReg', function(){
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=cancel_my_reg'
// 			, success: function(res) {
// 				let result = JSON.parse(res);
// 				if (result['error']===0)
// 				{
// 					alert(result['txt']);
// 					$('#overlay').click();
// 				}
// 				else
// 					alert(result['txt']);
// 			}
// 			, error: function(res) {
// 				alert('Error: Ошибка связи с сервером');
// 			}
// 		});
// 		return false;
// 	});
// 	
// 	$('#MainBody').on('change','select.select_role', function(){
// 		$('#ShufleGamers').addClass('hide');
// 	});
// 	$('#MainBody').off('click','#StartGame');
// 	$('#MainBody').on('click','#StartGame', function(){
// 		if ($('input[name="manager"]').val().trim() === '')
// 		{
// 			alert('Сначала выберите ведущего из списка не играющих игроков!')
// 			return false;
// 		}
// 		let check=0,maf=0,don=0,she=0, i=-1;
// 		while(++i<=9)
// 		{
// 			if ($('input[name="player['+i+']"]').val().trim() === '')
// 			{
// 				check=0;
// 				alert('Кого-то не хватает! (Нет игрока под №'+(i+1)+')');
// 				break;
// 			}
// 			check=$('select[name="role['+i+']"]').val();
// 			if (check!=0)
// 			{
// 				if (check==1) ++maf;
// 				else if (check==2) ++don;
// 				else if (check==4) ++she;
// 			}
// 		}
// 		if (maf===2 && don===1 && she===1)
// 		{
// 			$.ajax({
// 				url:'switcher.php'
// 				, type:'POST'
// 				, data:'need=game_start&'+$('#tempForm').serialize()+'&e='+EveningID
// 				, success: function(res) {
// 					window.location.href='/?g_id='+res;
// 				}
// 			});
// 			return false;
// 		}
// 		else
// 		{
// 			alert('Неправильно распределены роли!\r\n(Ролей всего: Мафии - 2, Дон - 1, Шериф - 1, Мирные - 6');
// 			return false;
// 		}
// 	});
// 	$('#MainBody').off('click','#PlayersArray span.player_name');
// 	$('#MainBody').on('click','#PlayersArray span.player_name', function(){
// 		if ($(this).hasClass('tmp_user'))
// 		{
// 			if (confirm('Игрок не может сесть за стол под временным именем.\r\nНазначить игровое имя?'))
// 				rename_player($(this).text());
// 		}
// 		else
// 		{
// 			if ($(this).hasClass('selected'))
// 				remove_player($(this).text())
// 			else
// 				add_player($(this).text());
// 			$(this).toggleClass('selected');
// 		}
// 	})
// 	$('#MainBody').off('click','#AddPlayersToArray');
// 	$('#MainBody').on('click','#AddPlayersToArray', function(){
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=add_evening_player_form'
// 			, success: function(res) {
// 				ModalEvent(res,'420,250');
// 				$('input.input_gamer').autocomplete({
// 					source: 'switcher.php?need=autocomplete_names&',
// 					minLength: 2
// 				});
// 			}
// 			, error: function(res) {
// 				alert('Error: Ошибка связи с сервером');
// 			}
// 		});
// 	});
// 	$('#MainBody').off('click','.del');
// 	$('#MainBody').on('click','.del', function(){
// 		let e = $(this);
// 		let pn = (e.prev('.player_name').text());
// 		if (confirm('Точно удалить игрока '+pn+' из записи?\r\nЗаработанные им сегодня балы могут не учитываться в статистике!'))
// 		{
// 			remove_player(pn);
// 			$.ajax({
// 				url:'switcher.php'
// 				, type:'POST'
// 				, data:'need=remove_player_from_evening&i='+e.attr('id').split('_')[1]
// 				, async: false
// 				, success: function(res) {
// 					e.prev('.player_name').remove();
// 					e.remove();
// 				}
// 			});
// 		}
// 	});
// 	$('#MainBody').off('change','input.input_name');
// 	$('#MainBody').on('change','input.input_name', function(){
// 		if (EveningID !== -1)
// 		{
// 			let name = $(this).val().trim();
// 			if (name === '') return false;
// 			if (!check_present(name) && confirm('Указанного игрока нет среди зарегистрированных на вечер. Добавить?'))
// 				add_evening_player(name);
// 			else alert('Уже зарегистрирован!');
// 		}
// 	});
// 	$('.modal_window').off('click','#AddNewGamer');
// 	$('.modal_window').on('click','#AddNewGamer', function(){
// 		let name = $('input[name=new_gamer]').val().trim();
// 		if (name === '') return false;
// 		if (!check_present(name))
// 		{
// 			add_evening_player(name);
// 			$('#overlay').click();
// 		}
// 		else alert('Уже зарегистрирован!');
// 	});
// 	$('#modal_block').off('click','#RenamePlayer');
// 	$('#modal_block').on('click','#RenamePlayer', function(){
// 		let old = $('#RenameEveningPlayer input[name="old_name"]').val();
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=rename_player&'+$('#RenameEveningPlayer').serialize()
// 			, success: function(res) {
// 				let result = JSON.parse(res);
// 				if (result['error']===0)
// 				{
// 					$('#PlayersArray span.player_name:contains("'+old+'")').text(result['nn']).removeClass('tmp_user').click();
// 					$('#overlay').click();
// 				}
// 				else
// 					alert(result['txt']);
// 			}
// 			, error: function(res) {
// 				alert('Error: Ошибка связи с сервером');
// 			}
// 		});
// 		return false;
// 	});
// 	$('#modal_block').off('click','#AddMe');
// 	$('#modal_block').on('click','#AddMe', function(){
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=add_me_to_evening&'+$('form#Form_MyConfirm').serialize()
// 			, success: function(res) {
// 				let result = JSON.parse(res);
// 				if (result['error']===0)
// 				{
// 					alert(result['txt']);
// 					$('#overlay').click();
// 				}
// 				else
// 					alert(result['txt']);
// 			}
// 			, error: function(res) {
// 				alert('Error: Ошибка связи с сервером');
// 			}
// 		});
// 		return false;
// 	});
// 	$('#MainBody').off('click','div.LogHeader');
// 	$('#MainBody').on('click','div.LogHeader', function(){
// 		let id = $(this).attr('id').split('_')[1];
// 		if ($('#Log_'+id).hasClass('hide'))
// 			open_log(id)
// 		else close_log(id);
// 	});
// 	$('#gameResume').click(function() {
// 		window.location.href='/?g_id='+$(this).val();
// 	});
// 	$('#MainBody').off('click','.arrows');
// 	$('#MainBody').on('click','.arrows', function(){
// 		if ($(this).hasClass('arr_disabled')) return false;
// 		$.ajax({
// 			url:'switcher.php'
// 			, type:'POST'
// 			, data:'need=show_history&e='+$(this).attr('id').split('_')[1]
// 			, success: function(res) {
// 				$('#MainBody').html(res);
// 			}
// 		});
// 	});
// });