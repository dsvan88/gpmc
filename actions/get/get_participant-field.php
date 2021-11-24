<?
$output['html'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/participant-field-edit.html');

$replace=[
			'{PARTICIPANT_INDEX}' => $_POST['id'],
			'{PARTICIPANT_NUMBER}' => $_POST['id']+1,
			'{PARTICIPANT_NAME}' => '',
			'{PARTICIPANT_ARRIVE}' => '',
			'{PARTICIPANT_DURATION_0}' => '',
			'{PARTICIPANT_DURATION_1}' => '',
			'{PARTICIPANT_DURATION_2}' => '',
			'{PARTICIPANT_DURATION_3}' => ''
		];

$output['html'] = str_replace(array_keys($replace), array_values($replace), $output['html']);