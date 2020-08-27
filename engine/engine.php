<?php
include $root_path.'/config/vars_default.php';
if (!defined('FUNC_LOAD'))	include 'functions.php';
if (!defined('BASE_LOAD'))	include 'engine_base.php';
if (isset($engine_set) && !defined($engine_set.'_LOAD')) include 'engine_'.strtolower($engine_set).'.php';