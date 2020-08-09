<body style="background:url(<?=$settings['img']['fon']['value']?>)">
<?
require $root_path.'/main/body/header.php';
if ($_SESSION['ba'] > 0) include $root_path.'/admin/admin.php';
else
{
	if (!isset($_GET['g_id']))
	{
		require $root_path.'/main/panels/left_panel.php';
		require $root_path.'/main/panels/right_panel.php';
	}
	require $root_path.'/main/body/main_body.php';
}
require $root_path.'/main/body/footer.php';
?>
</body>

