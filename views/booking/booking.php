<?php
if ($eveningsBooked){
    for ($i=0; $i < count($eveningsBooked); $i++) { 
        $EveningData = $eveningsBooked[$i];
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';
    }
    $output['{EVENING_SECTION}'] .= '
    <div class="booking__additional-evening">
        <div class="booking__buttons">
            <button type="button" title="Додати вечір гри" data-action="evening-add-booking-table"><i class="fa fa-plus-square"></i></button>
        </div>
    </div>';
}
else
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';

/* <?php
if ($eveningsBooked){
    for ($i=0; $i < count($eveningsBooked); $i++) { 
        $EveningData = $eveningsBooked[$i];
        if ($EveningData['start']){
            // if (count($EveningData['participants_info']) < 11)
                require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';
            // else
            //     require_once $_SERVER['DOCUMENT_ROOT'].'/views/game/game-prepeare.php';
        }
        else
            require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';
    }
    $output['{EVENING_SECTION}'] .= '
    <div class="booking__additional-evening">
        <div class="booking__buttons">
            <button type="button" title="Додати вечір гри"><i class="fa fa-plus-square"></i></button>
        </div>
    </div>';
}
else
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/evening-prepeare.php';

 */
