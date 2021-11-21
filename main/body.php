<body style="
	background-image: radial-gradient(#b59090c4, #11060642, #d3818142, #00000061), url(<?=$settings['img']['fon']['value']?>);
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
	if ($_SESSION['ba'] > 0) include $root_path.'/admin/admin.php';
	else
		require $root_path.'/main/main_body.php';
	?>
	</div>
</body>
