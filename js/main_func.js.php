<?header('Content-Type: text/javascript');?>
function open_log(id)
{
	$('#Log_'+id).removeClass('hide');
	$('#ShowLog_'+id).text('- Скрыть лог игры');
}
function close_log(id)
{
	$('#Log_'+id).addClass('hide');
	$('#ShowLog_'+id).text('+ Открыть лог игры');
}
function check_present(name)
{
	if ($('span.player_name:contains("'+name+'")').text() === name)
		return true;
	return false;
}
function rename_player(name)
{
	$.ajax({
		url:'switcher.php'
		, type:'POST'
		, data:'need=rename_player_form&n='+name
		, success: function(res) {
			ModalEvent(res,'420,250');
			$('input.input_gamer').autocomplete({
				source: 'switcher.php?need=autocomplete_names&',
				minLength: 2
			});
		}
	});
}
function remove_player(name)
{
	let i = -1;
	while(++i<=9)
	{
		if ($('input[name="player['+i+']"]').val() === name)
			$('input[name="player['+i+']"]').val('');
	}
	if ($('input[name="manager"]').val() === name)
		$('input[name="manager"]').val('');
}
function add_evening_player(name)
{
	$.ajax({
		url:'switcher.php'
		, type:'POST'
		, data:'need=add_new_player&n='+name
		, success: function(res) {
			$('#PlayersArray').html($('#PlayersArray').html()+res);
		}
	});
}
function add_player(name)
{
	let i = -1;
	let set = false;
	while(++i<=9)
	{
		if ($('input[name="player['+i+']"]').val() === '')
		{
			$('input[name="player['+i+']"]').val(name);
			set = true;
			break;
		}
	}
	if (!set)
		$('input[name="manager"]').val(name);
}
function inttotime(t)
{
	m = Math.floor(t/6000);
	s = Math.floor(t%6000/100);
	ms = t%100;
	return '0'+m+':'+(s > 9 ? s : '0'+s)+':'+(ms > 9 ? ms : '0'+ms);
}
function redirectPost(url, data) {
    var form = document.createElement('form');
    document.body.appendChild(form);
    form.method = 'post';
    form.action = url;
    for (var name in data) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}
function ModalEvent(html='',s='') {
	if (html !=='')	$('#modal_block .modal_block_body').html(html);
	let size = s === '' ? [420,250] : s.split(',');
	$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		function(){ // пoсле выпoлнения предъидущей aнимaции
			$('#modal_block') 
				.css({'display':'block',
					'width':size[0]+'px',
					'height':size[1]+'px',
					'margin-left': '-'+Math.round(size[0]/2)+'px',
					'margin-top': '-'+Math.round(size[1]/2)+'px'
				}) // убирaем у мoдaльнoгo oкнa display: none;
				.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		});
	/* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
	$('.modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
		$('#modal_block').animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
			function(){ // пoсле aнимaции
				$(this).css('display', 'none'); // делaем ему display: none;
				$('#overlay').fadeOut(400); // скрывaем пoдлoжку
			}
		);
	});
}
function AdditionalModalEvent(html='',s='',divID='add_modal_block') {
	if (!$("div").is('#'+divID))
		prepeare_add_modal_div(divID);
	$('#'+divID).html(html);
	let size = s === '' ? [420,320] : s.split(',');
	$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		function(){ // пoсле выпoлнения предъидущей aнимaции
			if ($('#'+divID).css('display') !== 'block')
			{
				$('#'+divID) 
					.css({'display':'block',
						'width':size[0]+'px',
						'height':size[1]+'px',
						'margin-left': '-'+Math.round(size[0]/2)+'px',
						'margin-top': '-'+Math.round(size[1]/2)+'px'
					}) // убирaем у мoдaльнoгo oкнa display: none;
					.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
			}
			else $('#'+divID).animate({
					'width': size[0]+'px',
					'height': size[1]+'px',
					'margin-left': '-'+Math.round(size[0]/2)+'px',
					'margin-top':'-'+Math.round(size[1]/2)+'px'},200);
		});
	/* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
	$('.modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
		$('#'+divID).animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
			function(){ // пoсле aнимaции
				$(this).css('display', 'none'); // делaем ему display: none;
				$('#overlay').fadeOut(400); // скрывaем пoдлoжку
				$('#'+divID).remove();
			}
		);
	});
}
function prepeare_add_modal_div(new_id)
{
	$('body').append('<div id="'+new_id+'" class="modal_window">'+$('div#modal_block').html()+'</div>');
}
function make_cropper()
{
	let img = $('#img_for_crop');
	img.cropper({
		aspectRatio: 3.5 / 4
		, minContainerWidth:325
		, minContainerHeight:220
		, checkOrientation:false
		, ready: function(event) {
			$('body').on('click','#CropMyAvatar', function(){
				$.ajax({
					url:'switcher.php'
					, data: 'need=crop_file&i='+$('input[name=filename]').val()+'&d='+JSON.stringify(img.data('cropper').getData(true))
					, type: 'POST'
					, success: function (res) {
						res = JSON.parse(res);
						if (res['error'] === 0)
							location.reload();
						else
							alert(res['html']);
					}
					, error: function(res) {
						alert('Error: Ошибка связи с сервером');
					}
				});
			});
		}
	});
}
function became_admin()
{
	$.ajax({
		url:'switcher.php'
		, type:'POST'
		, data:'need=admin_login_form'
		, success: function(res) {
			if (res === 'admin')
			{
				location.reload();
				return false;
			}
			else 
			{
				ModalEvent(res,'420,270');
				$('.modal_window').off('click','#LogInButton');
				$('.modal_window').on('click','#LogInButton', function(){
					$.ajax({
						url:'switcher.php'
						, type:'POST'
						, data:'need=login&'+$('#Form_AdminLogin').serialize()
						, success: function(res) {
							let result = JSON.parse(res);
							if (result['error']==$('#Form_AdminLogin input[name="ap"]').val())
								location.reload();
							else
								alert(result['txt']);
						}
						, error: function(res) {
							alert('Error: Ошибка связи с сервером');
						}
					});
					return false;
				});
			}
		}
		, error: function(res) {
			alert('Error: Ошибка связи с сервером');
		}
	});
}
Array.prototype.shuffle = function( b )
{
	var i = this.length, j, t;
	while( i ) 
	{
		j = Math.floor( ( i-- ) * Math.random() );
		t = b && typeof this[i].shuffle!=='undefined' ? this[i].shuffle() : this[i];
		this[i] = this[j];
		this[j] = t;
		  // for (let i = array.length - 1; i > 0; i--) {
    // let j = Math.floor(Math.random() * (i + 1)); // случайный индекс от 0 до i

    // поменять элементы местами
    // мы используем для этого синтаксис "деструктурирующее присваивание"
    // подробнее о нём - в следующих главах
    // то же самое можно записать как:
    // let t = array[i]; array[i] = array[j]; array[j] = t
    // [array[i], array[j]] = [array[j], array[i]];
  // }
	}
	return this;
};