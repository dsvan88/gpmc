<?php
if ($EveningData['start']){
    if (count($EveningData['participants_info']) < 11)
        require_once 'evening-prepeare.php';
    else
        require_once 'game-prepeare.php';
}