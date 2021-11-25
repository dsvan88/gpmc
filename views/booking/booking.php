<?php
if ($EveningData['start']){
    if (count($EveningData['participants_info']) < 11)
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';
    else
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/game/game-prepeare.php';
}
else
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';