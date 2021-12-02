<?php
if (!session_id()){
    session_start();
}

if (!defined('SQL_HOST'))
{
	if (isset($_ENV['DATABASE_URL'])){
		preg_match('/\/\/(.*?)\:(.*?)\@(.*?)\:(\d{1,5})\/(.*)/',$_ENV['DATABASE_URL'], $match);
		define('SQL_USER', 	$match[1]);
		define('SQL_PASS', 	$match[2]);
		define('SQL_HOST', 	$match[3]);
		define('SQL_PORT', 	$match[4]);
		define('SQL_DB', 	$match[5]);
	}
	else {
		define('SQL_HOST', 	'127.0.0.1');
		define('SQL_PORT', 	'5432');
		define('SQL_USER', 	'postgres');
		define('SQL_PASS', 	'');
		define('SQL_DB', 	'mafia');
	}
	define('SQL_TBLGAMES', 'games');
	define('SQL_TBLUSERS', 'users');
	// define('SQL_TBLSTATS', 'statistic');
	define('SQL_TBLEVEN', 'evenings');
	define('SQL_TBLPLACES', 'places');
	define('SQL_TBLSETTINGS', 'settings');
	define('SQL_TBLVOTES', 'votes');
	define('SQL_TBLCOMM', 'comments');
	define('SQL_TBLNEWS', 'news');

	define('DATE_MARGE', 36000); //36000 = +10 часов к длительности вечера
	define('TIME_MARGE', 1800); //1800 = за полчаса до официально старта - открывает регистрация игроков на первую игру

	define('CFG_DEBUG', true);
	define('CFG_NEWS_PER_PAGE', 6); 
	define('CFG_MAX_SESSION_AGE', 604800); // 60*60*24*7 == 1 week
	define('LOG_PREFIX', 'LogFile_');
	define('SCRIPT_VERSION', '0.16');
	define('MAFCLUB_NAME', 'Good People Mafia Club');
	define('MAFCLUB_SNAME', 'GPMC');
	define('FILE_USRGALL', '/gallery/users/');
	define('FILE_MAINGALL', '/gallery/site/images/');
}

if (CFG_DEBUG){
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}