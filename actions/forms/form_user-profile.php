<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.image-processing.php';

$users = new Users;
$images = new ImageProcessing;

$userId = $_SESSION['id'];
if (isset($_POST['userId']))
    $userId = $_POST['userId'];
$userData = $users->usersGetData(['*'], ['id' => $userId]);

$avatar = $userData['avatar'];
if ($avatar === '') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.settings.php';
    $settings = new Settings;
    $settingsArray = $settings->modifySettingsArray($settings->settingsGet(['short_name', 'name', 'value', 'type'], ['img']));
    $avatar = $settingsArray['img']['empty_avatar']['value'];
} else {
    $avatar = FILE_USRGALL . "$userId/$avatar";
}

$replace['{PROFILE_STATUS}'] = '';

if ($_SESSION['status'] === 'admin') {
    $replace['{PROFILE_STATUS}'] = '
        <div class="common-form__row">
            <label class="common-form__label" for="profile-status">Статус</label>
            <select class="common-form__select" id="profile-status" name="status"/>
                <option value="user" ' . ($userData['status'] === 'user' ? ' selected' : '') . '>Користувач</option>
                <option value="admin" ' . ($userData['status'] === 'admin' ? ' selected' : '') . '>Админ</option>
                <option value="manager" ' . ($userData['status'] === 'manager' ? ' selected' : '') . '>Менеджер</option>
            </select>
        </div>';
}
$replace['{PROFILE_AVATAR}'] = $images->inputImage($avatar, ['title' => 'Player avatar']);
$replace['{PROFILE_INDEX}'] = $userId;
$replace['{PROFILE_NAME}'] = $userData['name'];
$replace['{PROFILE_FIO}'] = $userData['fio'];
$replace['{PROFILE_BIRTHDAY}'] = $userData['birthday'] === 0 ? date('d.m.Y') : date('d.m.Y', $userData['birthday']);
$replace['{PROFILE_GENDER}'] = $userData['gender'];
$replace['{PROFILE_EMAIL}'] = $userData['email'];
$replace['{PROFILE_TELEGRAM}'] = $userData['telegram'];
$replace['{PROFILE_TELEGRAM_ID}'] = $userData['telegramid'];
$replace['{PROFILE_GENDER_UNSET}'] = $userData['gender'] == '' ? ' selected ' : '';
$replace['{PROFILE_GENDER_MALE}'] = $userData['gender'] == 'male' ? ' selected ' : '';
$replace['{PROFILE_GENDER_FEMALE}'] = $userData['gender'] == 'female' ? ' selected ' : '';
$replace['{PROFILE_GENDER_UNKNOW}'] = $userData['gender'] == 'unknow' ? ' selected ' : '';

$output['html'] = str_replace(array_keys($replace), array_values($replace), file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/templates/forms/form_user-profile.html'));
$output['jsFile'] = [
    'js/jquery-cropper.js',
    'js/get_script.php/?js=crop-images'
];
$output['cssFile'] = 'css/cropper.css';
