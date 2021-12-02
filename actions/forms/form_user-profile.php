<?

require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.image-processing.php';

$users = new Users;
$images = new ImageProcessing;

$userData = $users->usersGetData(['*'],['id'=>$_SESSION['id']]);

$avatar = $userData['avatar'];
if ($avatar === ''){
    require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';
    $settings = new Settings;
    $settingsArray = $settings->modifySettingsArray($settings->settingsGet(['short_name','name','value','type'],['img']));
    $avatar = $settingsArray['img']['empty_avatar']['value'];
}
else{
   $avatar = FILE_USRGALL.$_SESSION['id'].'/'.$avatar;
}

$replace['{PROFILE_AVATAR}'] = $images->inputImage($avatar,['title'=>'Player avatar']);
$replace['{PROFILE_NAME}'] = $userData['name'];
$replace['{PROFILE_FIO}'] = $userData['fio'];
$replace['{PROFILE_BIRTHDAY}'] = $userData['birthday'] === 0 ? date('d.m.Y') : date('d.m.Y',$userData['birthday']);
$replace['{PROFILE_GENDER}'] = $userData['gender'];
$replace['{PROFILE_EMAIL}'] = $userData['email'];
$replace['{PROFILE_TELEGRAM}'] = $userData['telegram'];
$replace['{PROFILE_GENDER_UNSET}'] = $userData['gender'] == '' ? ' selected ' : '';
$replace['{PROFILE_GENDER_MALE}'] = $userData['gender'] == 'male' ? ' selected ' : '';
$replace['{PROFILE_GENDER_FEMALE}'] = $userData['gender'] == 'female' ? ' selected ' : '';
$replace['{PROFILE_GENDER_UNKNOW}'] = $userData['gender'] == 'unknow' ? ' selected ' : '';

$output['html'] = str_replace(array_keys($replace),array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_user-profile.html'));
$output['jsFile'] = [
                    'js/jquery-cropper.js',
                    'js/get_script.php/?js=crop-images'
                ];
$output['cssFile'] = 'css/cropper.css';

/* [id] => 2
    [name] => Джокер
    [rank] => 0
    [status] => 
    [last_game] => 0
    [login] => demon
    [password] => $2y$10$.dzQXk.PMJpwX27S6lNYZeBnlrPvkTWw9lnDOp4GF94kHqiU9vYKW
    [fio] => 
    [birthday] => 0
    [gender] => 
    [email] => 
    [game_credo] => 
    [live_credo] => 
    [avatar] => 
    [admin] => 0
    [telegram] =>
    */