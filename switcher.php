<?
$root_path = $_SERVER['DOCUMENT_ROOT'];
$engine_set = 'JSFUNC';
require $root_path.'/engine/engine.php'; 
$engine = new JSFunc();
$_GET = _ft($_GET);
$need = trim(isset($_GET['need']) ? $_GET['need'] : $_POST['need']);
if ($need==='') exit('Wrong `need` type!');
$settings = $engine->ModifySettingsArray($engine->GetSettings(array('shname','name','value','type'),'img'));

$need_forms = ['my_record_form','add_evening_player_form','new_user_reg_form','login_form','admin_login_form','edit_my_info_form','rename_player_form'];
$need_action = ['login','new_user_registration','cancel_my_reg','remove_player_from_evening','discharge_player','add_new_player', 'apply_evening',
	'crop_file','edit_setting','edit_my_info','do_my_vote','save_comment','rename_player','edit_user_row','edit_point','add_new_player','upload_file'];
$need_autocomplete = ['autocomplete_names','autocomplete_places'];
$need_gets = ['check_vote','get_place_info','get_setting_img','get_setting_txt','edit_user_row_form','edit_point_form','show_user_avatar','show_my_avatar','get_browser'];

if (in_array($need,$need_forms,true))
{
	if ($need === 'admin_login_form' && isset($_SESSION['ar']) && $_SESSION['ar'] > 0)
	{
		$_SESSION['ba'] = $_SESSION['ba'] > 0 ? 0 : rand(1,1000);
		exit('admin');
	}
	?>
	<img class = 'left' src='../css/images/gmpc_emblem1.png' alt='emblem' title='<?=MAFCLUB_SNAME?>'/>
	<img class = 'right' src='../css/images/gmpc_emblem.png' alt='emblem' title='<?=MAFCLUB_SNAME?>'/>
	<div class='FormMaket'>
		<?require $root_path.'/templates/forms/'.$need.'.php';?>
	</div>
	<?
}
elseif (in_array($need,$need_action,true))
	require $root_path.'/php_scripts/action/'.$need.'.php';
elseif (in_array($need,$need_autocomplete))
{
	$method = $need === 'autocomplete_names' ? 'GetNamesAutoComplete' : 'GetPlacesAutoComplete';
	require $root_path.'/php_scripts/get/get_names.php';
}
elseif (in_array($need,$need_gets))
	require $root_path.'/php_scripts/get/'.(strpos($need,'get_setting_') !== false ? 'get_setting' : $need).'.php';
elseif ($need === 'logout')
	$engine->LogOut();
elseif ($need === 'gamer_field')
	require $root_path.'/templates/gamer_field.php';
elseif ($need === 'game_start')
	require $root_path.'/game/game_start.php';
elseif ($need === 'save_log' || $need === 'save_game')
	require $root_path.'/game/tech/save_progress.php';
elseif ($need === 'show_history')
	require $root_path.'/pages/show_history.php';
elseif ($need === 'show_voting')
	require $root_path.'/pages/show_voting.php';
else exit('Wrong `need` type!');