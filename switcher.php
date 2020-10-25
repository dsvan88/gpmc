<?
$root_path = $_SERVER['DOCUMENT_ROOT'];
$engine_set = 'JSFUNC';
require $root_path.'/engine/engine.php'; 
$engine = new JSFunc();
$_GET = _ft($_GET);
$need = trim(isset($_GET['need']) ? $_GET['need'] : $_POST['need']);
// error_log($need);
if ($need==='') exit('Wrong `need` type!');
$settings = $engine->ModifySettingsArray($engine->GetSettings(array('shname','name','value','type'),'img'));

if ($need === 'upload_file')
	$need = 're-crop-avatar_form';

$need_forms = [
	'add-gamer_form',
	'add-players-to-array_form',
	'admin-login_form',
	'apply-my-booking_form',
	'edit-my-info_form',
	'edit-settings-image_form',
	'get-kcfinder-browser_form',
	'login_form',
	're-crop-avatar_form',
	'rename-gamer_form',
	'set-vote_form',
	'user-avatar_form',
	'user-register_form'
];
$need_action = [
	'add_gamer',
	'admin-panel',
	'apply_evening',
	'apply-my-booking',
	'apply-new-points',
	'apply-setting',
	'apply-user-data',
	'cancel-my-booking',
	'crop_file',
	'discharge_gamer',
	'edit-my-info',
	'login',
	'remove-gamer',
	'rename_gamer',
	'save-comment',
	'set-vote',
	'user-registration'
];
$need_autocomplete = [
	'autocomplete_names',
	'autocomplete_places'
];
$need_gets = [
	'edit-points',
	'edit-user-row',
	'get_place_info',
	'get-vote-list',
	// 'show_my_avatar',
	// 'show_user_avatar'
];

if (in_array($need,$need_forms,true))
{
	if ($need === 'admin_login_form' && isset($_SESSION['ar']) && $_SESSION['ar'] > 0)
	{
		$_SESSION['ba'] = $_SESSION['ba'] > 0 ? 0 : rand(1,1000);
		exit('admin');
	}
	$output['error'] = 0;
	$output['html'] = '
	<div class="modal-container">
	'.
		$engine->checkAndPutImage('/css/images/gmpc_emblem1.png',['title'=>MAFCLUB_SNAME,'class'=>'modal-close left']).
		$engine->checkAndPutImage('/css/images/gmpc_emblem.png',['title'=>MAFCLUB_SNAME,'class'=>'modal-close right']).
		'<div class="form-maket">';
		require $root_path.'/templates/forms/'.$need.'.php';
		$output['html'] .= '
		</div>
	</div>';
	echo json_encode($output,JSON_UNESCAPED_UNICODE);
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
{
	if ($engine->LogOut()) exit(json_encode(["error"=> 0]));
}
elseif ($need === 'gamer-field')
	require $root_path.'/templates/gamer-field.php';
elseif ($need === 'game_start')
	require $root_path.'/game/game_start.php';
elseif ($need === 'save_log' || $need === 'save_game')
	require $root_path.'/game/tech/save_progress.php';
elseif ($need === 'show_history')
	require $root_path.'/pages/show_history.php';
/* elseif ($need === 'show_voting')
	require $root_path.'/pages/show_voting.php'; */
else exit('Wrong `need` type! '.$need);