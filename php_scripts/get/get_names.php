<?php
$EveningID = (int) $_GET['e'];
$result = '["'.substr($engine->$method($_GET['term'],$EveningID > 0 ? $EveningID : 0),0,-3).'"]';
if ($result !== '[""]')
	exit($result);