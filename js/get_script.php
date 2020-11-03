<?
$scriptName = $_GET['js'] || $_GET['php'];
$scriptType = isset($_GET['js']) ? 'js' : 'php';
// if (in_array($scriptName,['game','get-data']))
//     require_once './php/init.php';

header('Content-Type: text/javascript');
require "./scripts/$scriptType/{$_GET['js']}.$scriptType";