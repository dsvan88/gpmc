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
	</div><?
}?>
</div>