<script type='text/javascript' src='js/jquery-3.3.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui.min.js'></script>
<script type='text/javascript' src="js/jquery.datetimepicker.full.min.js"></script>
<script type='text/javascript' src='js/jquery.cleditor.min.js'></script>
<script defer type='text/javascript' src='js/get_js.php/?script=main_func'></script>
<script defer type='text/javascript' src='js/get_js.php/?script=main'></script>
<!-- <script type='text/javascript' src='js/main_func.js.php'></script>
<script type='text/javascript' src='js/main.js.php'></script> -->
<? if ($_SESSION['ba'] > 0):?>
	<script type='text/javascript' src="js/ckeditor/ckeditor.js"></script>
	<script type='text/javascript' src='js/admin.js.php'></script>
<?elseif (isset($_GET['profile'])):?>
	<script type='text/javascript' src='js/profile.js.php'></script>
	<script type='text/javascript' src='js/jquery-cropper.js'></script>
<?elseif (isset($_GET['trg']) && $_GET['trg'] ==='voting'):?>
	<script type='text/javascript' src='js/voting.js.php'></script>
<?elseif (isset($_GET['g_id'])):?>
	<script type='text/javascript' src='js/mafia_common_func.js.php'></script>
	<script type='text/javascript' src='js/mafia_func.js.php'></script>
	<script type='text/javascript' src='js/mafia.js.php'></script>
<?endif?>