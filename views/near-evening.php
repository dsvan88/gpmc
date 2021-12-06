<?php
$htmlFiles=[
	'booking'=>$_SERVER['DOCUMENT_ROOT'].'/templates/booking/booking-show.html',
	'participant_row'=>$_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-show.html',
];
require_once $_SERVER['DOCUMENT_ROOT'].'/views/booking/booking.php';

$output['{MAIN_CONTENT}'] = '
    <main class="main">
        <section class="section near-evening">'.$output['{EVENING_SECTION}'].'</section>
    </main>';
