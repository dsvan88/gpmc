<?php
/* define('BASE_LOAD',true);
class SQLBase
{
	private $SQL;
    function __construct(){
        try {
            $this->SQL = new PDO('pgsql:host='.SQL_HOST.';port='.SQL_PORT.';dbname='.SQL_DB,SQL_USER,SQL_PASS);
        }
        catch (PDOException $exeption){
            error_log("SQL Error!: " . $exeption->getMessage());
            die();
        }
    }
	function SQLClose()
	{
		$this->SQL = null;
	}
	// Исполняет SQL запрос.
	// $q - текст запроса
	// Возвращает результат запроса, если таковой имеется
	function query($q)
    {
        // error_log($q);
        // error_log(json_encode($a));
        return $this->SQL->query($q);
    }
	// Подготавливает и исполняет SQL запрос.
	// $q - текст запроса
	// $a - ассоциативный массив значений для запроса. Прим.: ['id' => '10'];
	// Возвращает результат запроса, если таковой имеется
	function prepQuery($q,$a)
    {
        // error_log($q);
        // error_log(json_encode($a));
        try {
            $stmt = $this->SQL->prepare($q);
            $stmt->execute($a);
            return $stmt;
        } catch (Throwable $th) {
            error_log('Error: '.$th->getFile().':'.$th->getLine().";\r\nMessage: ".$th->getMessage()."\r\nTrace:\r\n".$th->getTraceAsString());
            return false;
        }
    }
	// Подготавливает и исполняет одинаковых SQL запросов c разными данными.
	// $q - текст запроса
	// $a - ассоциативный массив значений для запроса. Прим.: [['id' => '10'], ['id' => '11']];
	// Возвращает результат запроса, если таковой имеется
	function prepMassQuery($q,$a)
    {
        $stmt = $this->SQL->prepare($q);
        try {
            for($x=0;$x<count($a);$x++)
                $stmt->execute($a[$x]);
            return $stmt;
        } catch (Throwable $th) {
            error_log('Error: '.$th->getFile().':'.$th->getLine().";\r\nMessage: ".$th->getMessage()."\r\nTrace:\r\n".$th->getTraceAsString());
            return false;
        }
    }
	// Проверка наличия записей в базе по критериям
    function recordExists($criteria,$table,$criteriaType='OR'){

		$keys = array_keys($criteria);
		$query = "SELECT COUNT(id) FROM $table WHERE ";

		for($x = 0; $x<count($keys); $x++){
			if (trim($criteria[$keys[$x]]) !== ''){
				$query .= $keys[$x]." = :$keys[$x] $criteriaType ";
			}
		}
		return ($this->getColumn($this->prepQuery(substr($query,0,-2-strlen($criteriaType)),$criteria)) > 0) ? true : false;
	}
	// Добавляет запись в MYSQL базу
	// $data - ассоциативный массив со значениями записи: array('column_name'=>'column_value',...)
	// $t - таблица в которую будет добавлена запись
	// возвращает последнюю запись из таблицы (id новой записи, если верно указан $g_id)
	function rowInsert($data,$t='')
	{
        $t = $t === '' ? SQL_TBLGAMES : $t;
        $preKeys = [];
        if (count($data) === count($data, COUNT_RECURSIVE)){
            $keys = array_keys($data);
            for ($x=0;$x<count($keys);$x++)
                $preKeys[$x] = ':'.$keys[$x];
            $this->prepQuery('INSERT INTO '.$t.' ('.implode(',',$keys).') VALUES ('.implode(',',$preKeys).')', $data);
            return $this->SQL->lastInsertId();
        }
        else{
            $keys = array_keys($data[0]);
            for ($x=0;$x<count($keys);$x++)
                $preKeys[$x] = ':'.$keys[$x];
            $this->prepMassQuery('INSERT INTO '.$t.' ('.implode(',',$keys).') VALUES ('.implode(',',$preKeys).')', $data);
            return true;
        }
	}
	// Обновляет запись в базе
	// $data - ассоциативный массив со значениями записи: array('column_name'=>'column_value',...)
	// $where - ассоциативный массив со значенниями по которым искать запись для обновления ('key'=>'value') ('id'=>1)
	// $table - таблица в которую будет добавлена запись
    function rowUpdate($data,$where,$table='')
    {
        $query ='UPDATE '.($table==='' ? SQL_TBLGAMES : $table).' SET ';
        
        foreach ($data as $k=>$v)
            $query .= "$k = :$k,";
        $conditon = ' WHERE ';
        foreach ($where as $k=>$v){
            if (isset($data[$k])){
                error_log(__METHOD__.': UPDATE cann’t work with same keys in UPDATE-array and UPDATE-conditions!');
                die();
            }
            $data[$k] = $v;
            $conditon .= "$k = :$k OR";
        }
        if ($conditon === ' WHERE '){
            error_log(__METHOD__.': There in no conditions for SQL UPDATE');
            die();
        }
        return $this->prepQuery(substr($query,0,-1).substr($conditon,0,-3), $data);
    }
	// Удаляет строку по её полю ID в таблице
    public function rowDelete($id, $table = ''){
        if ($table === '')
            $table = SQL_TBLGAMES;
        return $this->prepQuery("DELETE FROM $table WHERE id = ?", [$id]);
    }
	// Вибирает значение лишь одной колонки, быстрее, чем getRow($q)[0]
	function getColumn($q, $n=0)
	{
		return $q->fetchColumn($n);
	}
    // Разбирает результат запроса в простой массив
	function getRow($q)
	{
		return $q->fetch(PDO::FETCH_NUM);
	}
    // Разбирает результат запроса в ассоциативный массив
    function getAssoc($q)
	{
		return $q ? $q->fetch(PDO::FETCH_ASSOC) : $q;
	}
    // Перебирает результат запроса в двухмерный ассоциативный массив
    function getAssocArray($r)
	{
		$a = array();
		$i=0;
		while($row = $this->getAssoc($r))
		{
			foreach($row as $k=>$v)
				$a[$i][$k] = $v;
			++$i;
		}
		$r = null;
		return $a;
	}

	
	// // Создание заголовка таблицы
	// // $a - ассоциативный массив, где ключ - имя поля, а значение - ширина его.
	// function makeTableHeader($a)
	// {
	// 	$o = '
	// 	<thead><tr>';
	// 	$c = count($a);
	// 	$th = array_keys($a);
	// 	$tw = array_values($a);
	// 	$i = -1;
	// 	while(++$i<$c)
	// 		$o .='<th'.($tw[$i] !== 0 ? ' width='.$tw[$i].'px' : '').'>'.str_replace('  ','<br>', $th[$i]);
	// 	$o .='</tr>
	// 	<tr>';
	// 	$i = 0;
	// 	while(++$i<=$c)
	// 		$o.='<th>'.$i;
	// 	return $o.'</tr></thead>';
	// } 
	function getSimpleString($r,$sep=',')
	{
		$s = '';
		while($row = $this->getColumn($r))
			$s .= $row.$sep;
		return $s;
	}
	function getSimpleArray($r)
	{
		$a = array();
		while($row = $this->getRow($r))
			$a[$row[0]] = $row[1];
		return $a;
	}
	function getRawArray($r)
	{
		$a = array();
		while($row = $this->getColumn($r))
			$a[] = $row;
		return $a;
	}
// 	// Получить ассоциативный массив всех игроков, по заданным условиям.
// 	function getGamerData($columns = ['*'],$conditions='',$limit=1)
// 	{
// 		$method = ($limit !== 1) ? 'getAssocArray' : 'getAssoc';
// 		$where = '';
// 		$columns = implode(',',$columns);
// 		$table = SQL_TBLUSERS;
// 		$values = [];
// 		if ($conditions !== '')
// 		{
// 			$where = ' WHERE ';
// 			foreach($conditions as $key=>$value)
// 			{
// 				if (!is_array($value)){
// 					$where .= "$key = ? AND ";
// 					$values[] = $value;
// 				}
// 				else {
// 					$where .= $key.' IN ('.substr(str_repeat('?,', count($value)),0,-1).') AND ';
// 					$values[] = array_merge($values, $value);
// 				}

// 			}
// 			$where = mb_substr($where,0,-4);
// 		}
// 		return $this->$method($this->prepQuery("SELECT $columns FROM $table $where".($limit !== 0 ? ' LIMIT '.$limit : ''), $values));
// 	}
// 	// Получение имени игрока по его ID в системе
// 	function getGamerName($id)
// 	{
// 		if ($result = $this->getColumn($this->prepQuery('SELECT name FROM '.SQL_TBLUSERS.' WHERE id = ? LIMIT 1')))
// 			return $result;
// 		else return '';
// 	}
// 	// Получить количество всех игроков в системе.
// 	function GetGamerCount()
// 	{
// 		return $this->getColumn($this->query('SELECT count(id) FROM '.SQL_TBLUSERS));
// 	}
	function DBInit()
	{
		$action->query(
			str_replace('{SQL_TBLCOMM}', SQL_TBLCOMM,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLCOMM} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					author INT NOT NULL DEFAULT '0',
					type CHARACTER VARYING(30) NOT NULL DEFAULT '',
					target INT NOT NULL DEFAULT '0',
					text TEXT NULL DEFAULT NULL
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLEVEN}', SQL_TBLEVEN,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLEVEN} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					date INT NOT NULL DEFAULT '0',
					place INT NOT NULL DEFAULT '0',
					evening_start TIMESTAMP NOT NULL DEFAULT '0',
					evening_last TIMESTAMP NOT NULL DEFAULT '0',
					games CHARACTER VARYING(100) NOT NULL DEFAULT '',
					participants CHARACTER VARYING(100) NOT NULL DEFAULT '',
					participants_info TEXT NULL DEFAULT NULL,
					status CHARACTER VARYING(20) NOT NULL DEFAULT 'new',
					times CHARACTER VARYING(150) NOT NULL DEFAULT '',
					tobe CHARACTER VARYING(60) NOT NULL DEFAULT '',
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLGAMES}', SQL_TBLGAMES,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLGAMES} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					eid INT NOT NULL DEFAULT '0',
					player_ids CHARACTER VARYING(100) NOT NULL DEFAULT '',
					win INT NOT NULL DEFAULT '0',
					players TEXT NULL DEFAULT NULL,
					vars TEXT NULL DEFAULT NULL,
					txt CHARACTER VARYING(100) NOT NULL DEFAULT '',
					manager INT NOT NULL DEFAULT '0',
					rating INT NOT NULL DEFAULT '0',
					start INT NOT NULL DEFAULT '0',
					games CHARACTER VARYING(100) NOT NULL DEFAULT '',
					start TIMESTAMP NOT NULL DEFAULT '0',
					end TIMESTAMP NOT NULL DEFAULT '0'
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLNEWS}', SQL_TBLNEWS,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLNEWS} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					title CHARACTER VARYING(250) NOT NULL DEFAULT '',
					subtitle CHARACTER VARYING(250) NOT NULL DEFAULT '',
					logo CHARACTER VARYING(250) NOT NULL DEFAULT '',
					html TEXT NULL DEFAULT NULL,
					date_add TIMESTAMP NOT NULL DEFAULT '0',
					date_delete INT NOT NULL DEFAULT '0',
					type VARCHAR(50) NOT NULL DEFAULT 'news'
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLPLACES}', SQL_TBLPLACES,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLPLACES} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					name CHARACTER VARYING(250) NOT NULL DEFAULT '',
					info CHARACTER VARYING(250) NOT NULL DEFAULT '',
					rating INT NOT NULL DEFAULT '0'
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLSETTINGS}', SQL_TBLSETTINGS,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLSETTINGS} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					type CHARACTER VARYING(30) NOT NULL DEFAULT 'pages',
					short_name CHARACTER VARYING(50) NOT NULL DEFAULT '',
					name CHARACTER VARYING(150) NOT NULL DEFAULT '',
					value TEXT NULL DEFAULT NULL,
					default TEXT NULL DEFAULT NULL
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLVOTES}', SQL_TBLVOTES,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLVOTES} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					type CHARACTER VARYING(30) NOT NULL DEFAULT '',
					object INT NOT NULL DEFAULT '0',
					name CHARACTER VARYING(250) NOT NULL DEFAULT '',
					text TEXT NULL DEFAULT NULL,
					author INT NOT NULL DEFAULT '0',
					open SMALLINT NOT NULL DEFAULT '0',
					started TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
				);"
			)
		);
		$action->query(
			str_replace('{SQL_TBLUSERS}', SQL_TBLUSERS,
				"CREATE TABLE IF NOT EXISTS {SQL_TBLUSERS} (
					id INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
					name CHARACTER VARYING(250) NOT NULL DEFAULT '',
					rank SMALLINT NOT NULL DEFAULT '0',
					status SMALLINT NOT NULL DEFAULT '0',
					last_game SMALLINT NOT NULL DEFAULT '0',
					login CHARACTER VARYING(250) NOT NULL DEFAULT '',
					password CHARACTER VARYING(250) NOT NULL DEFAULT '',
					fio CHARACTER VARYING(250) NOT NULL DEFAULT '',
					birthday INT NOT NULL DEFAULT '0',
					gender SMALLINT NOT NULL DEFAULT '0',
					email CHARACTER VARYING(250) NOT NULL DEFAULT '',
					game_credo TEXT NULL DEFAULT NULL,
					live_credo TEXT NULL DEFAULT NULL,
					avatar CHARACTER VARYING(250) NOT NULL DEFAULT '',
					admin SMALLINT NOT NULL DEFAULT '0'
				);"
			)
		);
		return false;
	}
} */