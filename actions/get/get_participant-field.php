<?
$output['html'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/participant-field.html');
$replace['{PARTICIPANT_INDEX}'] = $_POST['id'];
$replace['{PARTICIPANT_NUMBER}'] = $_POST['id']+1;

$output['html'] = str_replace(array_keys($replace), array_values($replace), $output['html']);