<?header('Content-Type: text/javascript');?>
$(function(){
	$('#AllVotings').off('click','.VotingHeaderDiv');
	$('#AllVotings').on('click','.VotingHeaderDiv', function(){
		let vi = $(this).next('.VotingInfo')
		if(!vi.hasClass('active'))
		{
			$('.VotingInfo.active').slideUp('slow');
			$('.VotingInfo.active').removeClass('active');
			vi.slideDown('slow');
			vi.addClass('active');
		}
		else
		{
			vi.slideUp('slow');
			vi.removeClass('active');
		}
	});
	$('.vote_tabs_row').off('click','.span_tabs');
	$('.vote_tabs_row').on('click','.span_tabs', function(){
		$('.span_tabs.active').removeClass('active');
		$(this).addClass('active');
		let type = $(this).attr('id') == 'ClosedVotings' ? '0' : '1';
		$.ajax({
			// url:'templates/pages/show_voting.php'
			url:'switcher.php'
			, type:'POST'
			, data:'need=show_voting&op='+type
			, success: function(res) {
				$('#AllVotings').html(res);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		});
	});
	$('#MainBody').off('click','.span_vote');
	$('#MainBody').on('click','.span_vote', function(){
		let vID=$(this).parents('.VotingInfo').attr('id');
		$.ajax({
			url:'switcher.php'
			, type:'POST'
			, data:'need=do_my_vote&v='+vID+'&html='+$('textarea#comm_'+vID).val()+'&m='+($(this).attr('id') === 'MyVoteFor' ? '1' : '0')
			, success: function(res) {
				res = JSON.parse(res);
				if (res['error']=='0')
				{
					alert(res['txt']);
					location.reload();
				}
				else
					alert(res['txt']);
			}
			, error: function(res) {
				alert('Error: Ошибка связи с сервером');
			}
		});
	});
});