<?header('Content-Type: text/javascript');
error_log($_GET['script']);
require($_GET['script'].'.js');