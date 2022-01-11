<?
$scriptName = $_GET['js'] || $_GET['php'];
$scriptType = isset($_GET['js']) ? 'js' : 'php';

header('Content-Type: text/javascript; charset=utf-8');
echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/js/scripts/$scriptType/$_GET[js].$scriptType");
/* 
$script = preg_replace('/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', file_get_contents($_SERVER['DOCUMENT_ROOT']."/js/scripts/$scriptType/$_GET[js].$scriptType"));
$script = preg_replace('/\/\/.*?(\r\n|\r|\n)/', "", $script);
$script = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $script); 

echo $script;
*/