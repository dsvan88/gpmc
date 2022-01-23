<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.weeks.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/engine/class.users.php';

$weeks = new Weeks;
$users = new Users;

$data = [
    'weekId' => (int) $_POST['weekId'],
    'dayId' => (int) $_POST['dayId'],
    'prim' => $_POST['prim'],
    'game' => trim($_POST['game']),
    'mods' => [],
    'time' => $_POST['day_time'],

];

if (isset($_POST['mods-fans'])) {
    $data['mods'][] = 'fans';
}
if (isset($_POST['mods-tournament'])) {
    $data['mods'][] = 'tournament';
}

if (isset($_POST['participant'])) {
    $data['participants'] = [];
    for ($i = 0; $i < count($_POST['participant']); $i++) {

        $userName = trim($_POST['participant'][$i]);

        if ($userName === '') continue;

        if ($userName !== '+1')
            $userId = $users->userGetId($userName);
        else {
            $userId = -1;
        }

        if ($userId === 0) {
            $userId = $users->userAdd($userName);
        }

        $data['participants'][] = [
            'id' => $userId,
            'name' => $userName,
            'arrive' => $_POST['arrive'][$i],
            'duration' => $_POST['duration'][$i],
        ];
    }
}

$output['weekId'] = $weeks->daySetApproved($data);

$output['message'] = $output['weekId'] ? 'Затверджено!' : 'Помилка!';
