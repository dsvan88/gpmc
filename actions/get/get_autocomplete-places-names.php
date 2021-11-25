<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.places.php';

$places = new Places();

$output['result'] = $places->placeGetNameAutoComplete($_POST['term']);