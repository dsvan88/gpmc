<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
// require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.image-processing.php';

$users = new Users;
// $images = new ImageProcessing;

$replace['{USERS_LIST}'] = '
    <table class="users-list">
        <thead>
            <tr>
                <th>#</th>
                <th>Псевдонім</th>
                <th>Логін</th>
                <th>Статус</th>
                <th>Гендер</th>
                <th>E-mail</th>
                <th>Telegram</th>
                <th>Меню</th>
            </tr>
        </thead>
        <tbody>';

$userData = $users->usersGetData(['*'],'',0);

for ($x=0; $x < count($userData); $x++) { 
    $replace['{USERS_LIST}'] .= '
            <tr>
                <td>'.($x+1).".</td>
                <td>{$userData[$x]['name']}</td>
                <td>{$userData[$x]['login']}</td>
                <td>{$userData[$x]['status']}</td>
                <td>{$userData[$x]['gender']}</td>
                <td>{$userData[$x]['email']}</td>
                <td>{$userData[$x]['telegram']}</td>
                <td>
                    <i class='fa fa-pencil-square-o news-dashboard__button' data-action='user-profile-form' data-user-id='{$userData[$x]['id']}' title='Редагувати'></i>
                </td>
            </tr>
    ";
}

$replace['{USERS_LIST}'] .= '
        </tbody>
    </table>';

/* 
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

$replace['{PROFILE_AVATAR}'] = $images->inputImage($avatar,['title'=>'Player avatar']); */

$output['html'] = str_replace('{USERS_LIST}',$replace['{USERS_LIST}'], file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_users-list.html'));
// $output['html'] = str_replace('{USERS_LIST}','Скоро будет готово', file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_users-list.html'));


// $replace['{PROFILE_NAME}'] = $userData['name'];
// $replace['{PROFILE_FIO}'] = $userData['fio'];
// $replace['{PROFILE_BIRTHDAY}'] = $userData['birthday'] === 0 ? date('d.m.Y') : date('d.m.Y',$userData['birthday']);
// $replace['{PROFILE_GENDER}'] = $userData['gender'];
// $replace['{PROFILE_EMAIL}'] = $userData['email'];
// $replace['{PROFILE_TELEGRAM}'] = $userData['telegram'];
// $replace['{PROFILE_GENDER_UNSET}'] = $userData['gender'] == '' ? ' selected ' : '';
// $replace['{PROFILE_GENDER_MALE}'] = $userData['gender'] == 'male' ? ' selected ' : '';
// $replace['{PROFILE_GENDER_FEMALE}'] = $userData['gender'] == 'female' ? ' selected ' : '';
// $replace['{PROFILE_GENDER_UNKNOW}'] = $userData['gender'] == 'unknow' ? ' selected ' : '';

// $output['html'] = str_replace(array_keys($replace),array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_user-profile.html'));
// $output['jsFile'] = [
//                     'js/jquery-cropper.js',
//                     'js/get_script.php/?js=crop-images'
//                 ];
// $output['cssFile'] = 'css/cropper.css';

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