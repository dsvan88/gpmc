<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';

$users = new Users;

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

$output['html'] = str_replace('{USERS_LIST}',$replace['{USERS_LIST}'], file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/forms/form_users-list.html'));
