<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.games.php';

$users = new Users;
$games = new Games;
$gameLogs = new GameLogs;

$players = $games->playersSetDefaultRoles($_POST);
$players = $users->usersGetIds($players);

$ids = $players['ids'];
unset($players['ids']);

$games->gameBegin((int) $_POST['evening'],$ids,$players,$_POST['manager']);
$gameLogs->gameLogRecordFile($_SESSION['id_game'],date('d.m.Y H:i').': Игра успешно начата!');

$output['gid'] = $_SESSION['id_game'];