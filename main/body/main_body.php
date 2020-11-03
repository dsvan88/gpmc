<div class='content'>
<?
if (isset($_GET['profile'])){
	echo '$_GET["profile"]='.$_GET['profile'];
	include $root_path.'/profile/profile.php';
}
elseif(isset($_GET['trg']))
	include $root_path.'/pages/show_pages.php';
elseif(isset($_GET['g_id']))
	include $root_path.'/game/game.php';
?>
</div>