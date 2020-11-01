<body style="
	background-image: radial-gradient(#ffffff42, #b3929242, #f266661c), url(<?=$settings['img']['fon']['value']?>);
	background-blend-mode: hard-light;
<?
[$x, $y] = getimagesize($root_path.'/'.$settings['img']['fon']['value']);
if ($x > 410 || $y >410):
	?>
		background-position: center;
		background-repeat: no-repeat;
		background-size: contain
	<?
endif;
?>">
<div class="wrapper">
<?
require $root_path.'/main/body/header.php';
if ($_SESSION['ba'] > 0) include $root_path.'/admin/admin.php';
else
{
	// if (!isset($_GET['g_id']))
	if ($_SERVER['REQUEST_URI'] === '/')
	{
		require $root_path.'/main/panels/left_panel.php';
		require $root_path.'/main/panels/right_panel.php';
	}
	require $root_path.'/main/body/main_body.php';
}
require $root_path.'/main/body/footer.php';
?>
</div>
</body>

