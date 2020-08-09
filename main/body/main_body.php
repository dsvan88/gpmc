<div class='content'>
<?
if (isset($_GET['profile']))
	include $root_path.'/profile/Profile.php';
elseif(isset($_GET['trg']))
	include $root_path.'/pages/show_pages.php';
elseif(isset($_GET['g_id']))
	include $root_path.'/game/game.php';
else
{
	?>
	<div class='content__register-evening'>
	<?
	$days = array( 'Воскресенье', 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота');
	$plan_tobe = array('','на 1-2 игры', 'на 2-3 игры', 'на 3-4 игры');
	if ($EveningData['ready'] !== false) 
	{
		$EveningData['time'] = date('H:i',$EveningData['date']);
		$EveningData['place'] = $EveningData['place'] === '0' ? array('id'=>'0','name'=>'','info'=>'') : $engine->GetPlaceByID($EveningData['place']);
		if ($EveningData['players'] !== '')
			$EveningData['players'] = $engine->GetPlayersNames($EveningData['players'],true);
		if ($EveningData['times'] !== '')
			$EveningData['times'] = explode(',',$EveningData['times']);
		if ($EveningData['tobe'] !== '')
			$EveningData['tobe'] = explode(',',$EveningData['tobe']);
	}
	if ($EveningData['start'] === false)
	{
		
		if(isset($_SESSION['status']))
		{
			if($_SESSION['status'] > 0)	require $root_path.'/evening/evening_prepeare_resident.php';
			else require $root_path.'/evening/evening_prepeare_user.php';
		}
		else require $root_path.'/evening/evening_prepeare_guest.php';
		
	}
	else
	{
		if(isset($_SESSION['status']))
		{
			if($_SESSION['status'] > 0) require $root_path.'/evening/evening_in_progress_resident.php';
			else require $root_path.'/evening/evening_in_progress_user.php';
		}
		else require $root_path.'/evening/evening_in_progress_guest.php';
	}
	?></div><?
}?>
</div>