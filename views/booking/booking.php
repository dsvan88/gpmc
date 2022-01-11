<?php
if ($eveningsBooked) {
    $output['{EVENING_SECTION}'] = '';
    for ($i = 0; $i < count($eveningsBooked); $i++) {
        $eveningHtml = '';
        $EveningData = $eveningsBooked[$i];
        require $_SERVER['DOCUMENT_ROOT'] . '/views/booking/evening-prepeare.php';
        $output['{EVENING_SECTION}'] .= $dayHtml;
    }
    $output['{EVENING_SECTION}'] .= '
    <datalist id="users-names-list"></datalist>
    <div class="booking__additional-evening">
        <div class="booking__buttons">
            <button type="button" title="Додати вечір гри" data-action="evening-add-booking-table"><i class="fa fa-plus-square"></i></button>
        </div>
    </div>';
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/views/booking/evening-prepeare.php';
    $output['{EVENING_SECTION}'] = $dayHtml;
}
