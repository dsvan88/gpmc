<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';

$evenings = new Evenings;
$eveningId = $evenings->eveningGetId($_SERVER['REQUEST_TIME']);
error_log($_POST['id']);
$evenings->playerRemoveFromEvening($eveningId,(int)$_POST['id']);