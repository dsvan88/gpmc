<script type='text/javascript' src='js/jquery-3.3.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui.min.js'></script>
<script type='text/javascript' src="js/jquery.datetimepicker.full.min.js"></script>
<script type='text/javascript' src='js/jquery.cleditor.min.js'></script>
<script defer type='text/javascript' src='js/get_script.php/?js=main_func'></script>
<script defer type='text/javascript' src='js/get_script.php/?js=main'></script>
<? if (isset($_SESSION['ba']) && $_SESSION['ba'] > 0):?>
	<script defer type='text/javascript' src="js/ckeditor/ckeditor.js"></script>
	<script defer type='text/javascript' src='js/get_script.php/?js=admin'></script>
<?endif?>
<?if (isset($_SESSION['id']) && $_SESSION['id'] > 0):?>
	<script defer type='text/javascript' src='js/get_script.php/?js=users_scripts'></script>
<?endif?>
<?if (isset($_GET['profile'])):?>
	<script defer type='text/javascript' src='js/get_script.php/?js=profile'></script>
	<script defer type='text/javascript' src='js/jquery-cropper.js'></script>
<?endif?>
<?if (isset($_GET['trg'])):
	if ($_GET['trg'] ==='voting'):?>
		<script defer type='text/javascript' src='js/get_script.php/?js=voting'></script>
	<?elseif ($_GET['trg'] ==='evening'):?>
		<script defer type='text/javascript' src='js/get_script.php/?js=evening'></script>
	<?endif;?>
<?endif;?>
<?if (isset($_GET['g_id'])):?>
	<script defer type='text/javascript' src='js/get_script.php/?js=game'></script>
	<!-- <script defer type='text/javascript' src='js/get_script.php/?js=mafia_common_func'></script>
	<script defer type='text/javascript' src='js/get_script.php/?js=mafia_func'></script>
	<script defer type='text/javascript' src='js/get_script.php/?js=mafia'></script> -->
<?endif?>