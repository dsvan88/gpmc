<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.games.php';
$games = new Games;

$gameData = $games->gameLoadData($_POST['gid']);
if ($gameData)
    $output = array_merge($output,$gameData);
else{
    $output['error'] = 1;
    $output['text'] = 'Something wrong with game load functions';
}