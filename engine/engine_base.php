<?php
define('BASE_LOAD',true);
class SQLBase
{
	protected $SQL;
	function __construct() 
	{
		$this->Connect();
	}
	function Connect()
	{
		$this->SQL = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if(mysqli_connect_errno()) error_log('SQL Connect ERROR: '.mysqli_connect_error());
		mysqli_set_charset($this->SQL, 'utf8');
	}
	function SQLClose()
	{
		mysqli_close($this->SQL);
	}
	// Исполняет MYSQL запрос.
	// $q - текст запроса
	// Возвращает результат запроса, если таковой имеется
	function Query($q)
	{
		$r = mysqli_query($this->SQL, $q);
		if (!$r)
			error_log(__METHOD__.': SQL ERROR: '.mysqli_error($this->SQL).' Query: '.$q);
		// error_log('Query: '.$q);
		return $r;
	}
	// Разбирает результат запроса в простой массив
	function GetRow($q)
	{
		return mysqli_fetch_row($q);
	}
	// Разбирает результат запроса в ассоциативный массив
	function GetAssoc($q)
	{
		return mysqli_fetch_assoc($q);
	}
	// Добавляет запись в базу
	// $a - ассоциативный массив со значениями записи: array('column_name'=>'column_value',...)
	// $g_id - ID элемента массива $a, по которому будет извлекаться id последней добавленной записи из базы
	// $t - таблица в которую будет добавлена запись	
	#function InsertRow($a,$g_id=0,$t='')
	function InsertRow($a,$t='')
	{
		$k = array_keys($a);
		$v = array_values($a);
		$t = $t === '' ? MYSQL_TBLGAMES : $t;
		$this->Query('INSERT INTO `'.$t.'` (`'.implode('`,`',$k).'`) VALUES ("'.implode('","',$v).'")');
		return mysqli_insert_id($this->SQL);
		//$this->GetRow($this->Query('SELECT `id` FROM `'.$t.'` WHERE `'.$k[$g_id].'` = "'.$v[$g_id].'" ORDER BY `id` DESC LIMIT 1'))[0];
	}
	// Обновляет запись в базе
	// $a - ассоциативный массив со значениями записи: array('column_name'=>'column_value',...)
	// $f - ассоциативный массив со значенниями по которым искать запись для обновления ('key'=>'value') ('id'=>1)
	// $t - таблица в которую будет добавлена запись
	function UpdateRow($a,$f,$t='')
	{
		$q ='UPDATE `'.($t==='' ? MYSQL_TBLGAMES : $t).'` SET ';
		foreach ($a as $k=>$v)
			$q .= '`'.$k.'` = "'.$v.'",';
		$c = ' WHERE ';
		foreach ($f as $k=>$v)
			$c .= '`'.$k.'` = "'.$v.'" OR';
		$this->Query(substr($q,0,-1).substr($c,0,-3));
	}
	// Работа с настройками сайта:
	// $c - какие колонки выбрать из таблицы настройками
	// $t - тип натсройки: pages - Html код страниц сайта, txt - тексты сайта (типа приветствия, ещё какой-то лабуды), img - пути к основным картинкам сайта (лого, история, приветствия)
	// $f - настройки выборки - ассоциативный массив, где ключ - имя колонки для выборки, значение - её значение, для выборки
	function GetSettings($c,$t='pages',$f='')
	{
		if ($r = $this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLSETTINGS.'` WHERE '.(is_array($t) ? '`type` IN ("'.implode('","', $t).'")' : '`type`="'.$t.'"').(!is_array($f) ? '' : ' AND `'.array_keys($f)[0].'` = "'.array_values($f)[0].'"')))
			return $this->MakeAssocArray($r);
		else return false;
	}
	// Изменение массива настроек для более удобного применения.
	function ModifySettingsArray($a)
	{
		$ret = [];
		for($x=0;$x<count($a);$x++)
		{
			$ret[$a[$x]['type']][$a[$x]['shname']]['name'] = $a[$x]['name'];
			$ret[$a[$x]['type']][$a[$x]['shname']]['value'] = $a[$x]['type'] === 'txt' ? str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$a[$x]['value']) : $a[$x]['value'];
		}
		return $ret;
	}
	// Работа с настройками сайта:
	// $c - какие колонки выбрать из таблицы настройками
	// $t - тип настройки: pages - Html код страниц сайта, txt - тексты сайта (типа приветствия, ещё какой-то лабуды), img - пути к основным картинкам сайта (лого, история, приветствия)
	function SetSettings($a)
	{
		if ($a['id'] !== 'add')
		{
			$this->UpdateRow($a,array('id'=>$a['id']),MYSQL_TBLSETTINGS);
			return $a['id'];
		}
		unset($a['id']);
		return $this->InsertRow($a,MYSQL_TBLSETTINGS);
	}
	// Создание заголовка таблицы
	// $a - ассоциативный массив, где ключ - имя поля, а значение - ширина его.
	function MakeTableHeader($a)
	{
		$o = '
		<thead><tr>';
		$c = count($a);
		$th = array_keys($a);
		$tw = array_values($a);
		$i = -1;
		while(++$i<$c)
			$o .='<th'.($tw[$i] !== 0 ? ' width='.$tw[$i].'px' : '').'>'.str_replace('  ','<br>', $th[$i]);
		$o .='</tr>
		<tr>';
		$i = 0;
		while(++$i<=$c)
			$o.='<th>'.$i;
		return $o.'</tr></thead>';
	}
	function GetUsersArray()
	{
		if ($r = $this->Query('SELECT `id`,`fio` FROM `'.MYSQL_TBLPLAYERS.'` WHERE '.($_SESSION['sector'] !== '0' ? '`sector` LIKE "%'.str_replace(',','% OR `sector` LIKE %',$_SESSION['sector']).'%"' : '`id` > 0')))
			return $this->MakeSimpleArray($r);
		else error_log(__METHOD__.': SQL ERROR');
	}
	function MakeSimpleString($r,$sep=',')
	{
		$s = '';
		while($row = $this->GetRow($r))
			$s .= $row[0].$sep;
		mysqli_free_result($r);
		return $s;
	}
	function MakeSimpleArray($r)
	{
		$a = array();
		while($row = $this->GetRow($r))
			$a[$row[0]] = $row[1];
		mysqli_free_result($r);
		return $a;
	}
	function MakeRawArray($r)
	{
		$a = array();
		while($row = $this->GetRow($r))
			$a[] = $row[0];
		mysqli_free_result($r);
		return $a;
	}
	function MakeAssocArray($r)
	{
		$a = array();
		$i=-1;
		while($row = $this->GetAssoc($r))
		{
			++$i;
			foreach($row as $k=>$v)
				$a[$i][$k] = $v;
		}
		mysqli_free_result($r);
		return $a;
	}
	function CheckFreeLogin($s)
	{
		if ($this->GetRow($this->Query('SELECT `id` FROM `'.MYSQL_TBLPLAYERS.'` WHERE `username`="'.$s.'" LIMIT 1'))[0] > 0)
			return false;
		return true;
	}
	// Получить ассоциативный массив всех игроков, по заданным условиям.
	function GetPlayerData($c,$b='',$l=1)
	{
		$method = ($l !== 1) ? 'MakeAssocArray' : 'GetAssoc';
		$where = '';
		if ($b !== '')
		{
			$where = ' WHERE ';
			foreach($b as $k=>$v)
			{
				if (!is_array($v))
					$where .= '`'.$k.'` = "'.$v.'" AND ';
				else $where .= '`'.$k.'` IN ("'.implode('","',$v).'") AND ';
			}
			$where = mb_substr($where,0,-4);
		}
		return $this->$method($this->Query('SELECT `'.implode('`,`',$c).'` FROM `'.MYSQL_TBLPLAYERS.'`'.$where.($l > 0 ? ' LIMIT '.$l : '')));
	}
	function ChangePass($r)
	{
		if ($r['np'] !== $r['np2'])
			return '0#ОШИБКА! Пароли не совпадают. Повторите ввод!';
		$r['op'] = md5($r['op']);
		$g = $this->GetAssoc($this->Query('SELECT `id`,`p` FROM `'.MYSQL_TBLPLAYERS.'` WHERE `id` = "'.$_SESSION['id'].'" LIMIT 1'));
		if ($r['op'] === $g['p'])
		{
			$this->UpdateRow(array('p'=>md5($r['np'])),array('id'=>$g['id']),'users');
			return '1#Пароль успешно изменён!';
		}
		else return '0#ОШИБКА! Не верно введён старый пароль!';
	}
	function DBInit()
	{
		return false;
	}
	function LogIn($l,$p)
	{
		$r = $this->GetAssoc($this->Query('SELECT `id`,`name`,`username`,`status`,`gender`,`ar` FROM `'.MYSQL_TBLPLAYERS.'` WHERE (`name`="'.$l.'" OR `username`="'.$l.'" OR `email`="'.$l.'") AND `pass`="'.$p.'" LIMIT 1'));
		if (isset($r['id']))
			$_SESSION = $r;
		else return false;
	}
	function AdminLogIn($l,$p)
	{
		$r = $this->GetAssoc($this->Query('SELECT `id`,`name`,`username`,`status`,`gender`,`ar` FROM `'.MYSQL_TBLPLAYERS.'` WHERE `username`="'.$l.'" AND `pass`="'.$p.'" AND `ar` > 0 LIMIT 1'));
		if (isset($r['id']))
			$_SESSION = $r;
		else return false;
	}
	function LogOut()
	{
		unset($_SESSION);
		session_destroy();
		mysqli_close($this->SQL);
	}


	function GetGameLog($id)
	{
		$txt = $this->ReadLogFile($id);
		if ($txt !== false)
			return $txt;
		else return 'LogFile not found!';
	}
	function RecordLogFile($fn,$t)
	{
		$path = dirname(__FILE__,2).'/Logs';
		if (!file_exists($path)) mkdir($path,0777,true);
		$file = fopen($path.'/'.LOG_PREFIX.$fn.'.txt','a+');
		fwrite($file, $t.PHP_EOL);
		fclose($file);
	}
	function ReadLogFile($fn){
		$path = dirname(__FILE__,2).'/Logs';
		$filename = $path.'/'.LOG_PREFIX.$fn.'.txt';
		if (!file_exists($path) || !file_exists($filename))
		{
			error_log($filename.' -- not found!');
			return false;
		}
		return file_get_contents($filename);
	}

	
	function imageToWebp($source,$output,$from='png'){
		$func = 'imagecreatefrom'.($from !== 'jpg' ? $from : 'jpeg');
		$image = $func($source);
		imagewebp($image,$output);
		imagedestroy($image);
	}
	function getAdditionalImage($source, $format, $type='webp'){
		$output = '';
		if ($type === 'webp'){
			$webp = str_replace(".$format",'.webp', $source);
			if (!file_exists($webp))
				$this->imageToWebp($source,$webp,$format);
			$output .= PHP_EOL.'<source srcset="'.$webp.'" type="'.mime_content_type($webp).'">';
		}
		elseif ($type === 'mini'){
			$mini = substr($source,0,strrpos($source,'.')).'-mini.'.$format;

			if (!file_exists($mini)) return '';
			if ($format !== 'webp'){
				$webp = str_replace(".$format",'.webp', $mini);
				if (!file_exists($webp))
					$this->imageToWebp($mini,$webp,$format);
				$output .= PHP_EOL.'<source srcset="'.$webp.'" media="(max-width: 576px)" type="'.mime_content_type($webp).'">';
			}
			$output .= PHP_EOL.'<source srcset="'.$mini.'" media="(max-width: 576px)" type="'.mime_content_type($mini).'">';
		}
		return PHP_EOL.$output;
	}
	function checkAndPutImage($source,$title,$options=[])
	{
		$output = "<picture>";
		$realPathToSource = $_SERVER['DOCUMENT_ROOT'].$source;
		$format = str_replace('image/','',mime_content_type($realPathToSource));
		if ($format === 'jpeg') $format = 'jpg';

		$output .= $this->getAdditionalImage($realPathToSource,$format,'mini');

		if ($format !== 'webp')
			$output .= $this->getAdditionalImage($realPathToSource,$format,'webp');

		return str_ireplace($_SERVER['DOCUMENT_ROOT'],'.', $output.PHP_EOL.
			'<img '.(!isset($options['class']) ? '' : 'class="'.$options['class'].'" ').'src="'.$source.'" title="'.$title.'" alt ="'.$title.'">
		 </picture>');
	}
}