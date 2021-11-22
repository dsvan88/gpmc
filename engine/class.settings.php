<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';

class Settings {
	private $action;
    function __construct(){
        $this->$action = new Action();
    }
    // Работа с настройками сайта:
	// $columns - какие колонки выбрать из таблицы настройками
	// $type - тип настройки: pages - Html код страниц сайта, txt - тексты сайта (типа приветствия, ещё какой-то лабуды), img - пути к основным картинкам сайта (лого, история, приветствия)
	// $by - настройки выборки - ассоциативный массив, где ключ - имя колонки для выборки, значение - её значение, для выборки
	function settingsGet($columns,$type='pages',$by='')
	{
		$columns = is_array($columns) ? implode(',',$columns) : $columns;
		$table = SQL_TBLSETTINGS;
		$values = [];
		if (is_array($type)){
			$conditions = 'type IN ('.substr(str_repeat('?,', count($type)),0,-1).')';
			$values = array_merge($values, $type);
		}
		else{
			$conditions = 'type = ?';
			$values[] = $type;
		}
		
		if (is_array($where)){
			$key = array_keys($where)[0];
			$conditions .= " AND $key = ? ";
			$values[] = $where[$key];
		}

		if ($r = $this->$action->prepQuery("SELECT $columns FROM $table WHERE $conditions", $values))
			return $this->$action->getAssocArray($r);
		else return false;
	}
	// Работа с настройками сайта:
	// pages - Html код страниц сайта, txt - тексты сайта (типа приветствия, футера, ещё какой-то лабуды), img - пути к основным картинкам сайта (лого, история, приветствия)
	function settingsSet($a)
	{
		if ($a['id'] !== 'add')
		{
			$this->$action->rowUpdate($a,array('id'=>$a['id']),SQL_TBLSETTINGS);
			return $a['id'];
		}
		unset($a['id']);
		return $this->$action->rowInsert($a,SQL_TBLSETTINGS);
	}
	// Изменение массива настроек для более удобного применения.
	function modifySettingsArray($a)
	{
		if (!$a) return false;
		$ret = [];
		for($x=0;$x<count($a);$x++)
		{
			$ret[$a[$x]['type']][$a[$x]['short_name']]['name'] = $a[$x]['name'];
			$ret[$a[$x]['type']][$a[$x]['short_name']]['value'] = $a[$x]['type'] === 'txt' ? str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$a[$x]['value']) : $a[$x]['value'];
		}
		return $ret;
	}
}