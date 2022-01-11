<?php
$htmlFiles = [
    'booking' => $_SERVER['DOCUMENT_ROOT'] . '/templates/booking/booking-show.html',
    'participant_row' => $_SERVER['DOCUMENT_ROOT'] . '/templates/participant-field-show.html',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/views/day-prepeare.php';
$output['{EVENING_SECTION}'] = $dayHtml;

$output['{MAIN_CONTENT}'] = '
    <main class="main">
        <section class="section near-evening">' . $output['{EVENING_SECTION}'] . '</section>
    </main>';
