<?php
class GameLogs {
    function gameLogGet($id)
	{
		$txt = $this->gameLogReadFile($id);
		if ($txt !== false)
			return $txt;
		else return 'LogFile not found!';
	}
	function gameLogRecordFile($fn,$t)
	{
		$path = $_SERVER['DOCUMENT_ROOT'].'/Logs';
		if (!file_exists($path)) mkdir($path,0777,true);
		$file = fopen($path.'/'.LOG_PREFIX.$fn.'.txt','a+');
		fwrite($file, $t.PHP_EOL);
		fclose($file);
	}
	function gameLogReadFile($fn){
		$path = $_SERVER['DOCUMENT_ROOT'].'/Logs';
		$filename = $path.'/'.LOG_PREFIX.$fn.'.txt';
		if (!file_exists($path) || !file_exists($filename))
		{
			error_log($filename.' -- not found!');
			return false;
		}
		return file_get_contents($filename);
	}
} 