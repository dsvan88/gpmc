<?php
if (!defined('MYSQL_HOST'))
{
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'mafadm');
	define('MYSQL_PASS', 'MAF_admin1234');
	define('MYSQL_DB', 'dsvan');
	define('MYSQL_TBLGAMES', 'games');
	define('MYSQL_TBLPLAYERS', 'players');
	define('MYSQL_TBLSTATS', 'statistic');
	define('MYSQL_TBLEVEN', 'evenings');
	define('MYSQL_TBLPLACES', 'places');
	define('MYSQL_TBLSETTINGS', 'settings');
	define('MYSQL_TBLVOTES', 'votes');
	define('MYSQL_TBLCOMM', 'comments');
	define('DATE_MARGE', 36000); //36000 = +10 часов к длительности вечера
	define('TIME_MARGE', 1800); //1800 = за полчаса до официально старта - открывает регистрация игроков на первую игру
	define('LOG_PREFIX', 'LogFile_');
	define('SCRIPT_VERSION', '0.14');
	define('MAFCLUB_NAME', 'Good People Mafia Club');
	define('MAFCLUB_SNAME', 'GPMC');
	define('PASS_SALT', 'VeriStronPassward_');
	define('FILE_USRGALL', '/gallery/users/');
}