<?php
include 'dir_cfg.php';
$engine_set = 'JSFUNC';
include $root_path.'/engine/engine.php'; 
$jsfunc = new JSFunc();
$_GET = _ft($_GET);
error_log($_GET['e']);
$ret = '["'.substr($jsfunc->GetNamesAutoComplete($_GET['term']),0,-3).'"]';
if ($ret !== '[""]')
	echo $ret;
