<?php
define('FUNC_LOAD',true);
function engineStart(){
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.action.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.settings.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.users.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.image-processing.php';

	$GLOBALS['CommonActionObject'] = new Action;

	return [
		file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/main-template.html'),
		new Settings,
		new Evenings,
		new Users,
		new ImageProcessing
	];
}
/* 
function _ft($t)
{
	$p = array("|\r\n|", "|\r|", "|\n|", "|\!BR\!\"|", "|^\"|", '|^"|', '|\="|', "| \"|", '| "|', "|\"|", '|"|', "|\'|", "|'|");
	$r = array(' «', ' «', ' «', '«', '«', '«', '=«', ' «', ' «', '»', '»', '’', '’', ' ',' ',' ');
	$r_html = array('!BR!','!BR!','!BR!','!BR!"', '"', '"', '="', ' "', ' "', '"', '"', '’', '’');
	$a_html = array('html');
	foreach($t as $k=>$v)
	{
		if (in_array($k,$a_html,true))
			$t[$k] = preg_replace($p,$r_html,$v);
		else
			$t[preg_replace($p,$r,$k)] = preg_replace($p,$r,$v);
	}
	return $t;
} */
//Фильтруем POST от всего что может сломать базу
// $_POST=_ft($_POST);
/*
	$p = array("|\r\n\"|", "|\r\"|", "|\n\"|", "|^\"|", '|^"|', '|\="|', "| \"|", '| "|', "|\"|", '|"|', "|\'|", "|'|", "|\r\n|", "|\r|", "|\n|");
	$r = array(' «', ' «', ' «', '«', '«', '=«', ' «', ' «', '»', '»', '’', '’', ' ',' ',' ');
	$r_html = array('!BR!«', '!BR!«', '!BR!«', '«', '«', '=«', ' «', ' «', '»', '»', '’', '’', '!BR!','!BR!','!BR!');

function _ft($t)
{
	$p = array("|\r\n\"|", "|\r\"|", "|\n\"|", "|^\"|", '|^"|', "| \"|", '| "|', "|\"|", '|"|', "|\'|", "|'|", "|\r\n|", "|\r|", "|\n|");
	$r = array('!BR!«', '!BR!«', '!BR!«', '«', '«', ' «', ' «', '»', '»', '’', '’', '!BR!','!BR!','!BR!');
	foreach($t as $k=>$v)
		$t[preg_replace($p,$r,$k)] = preg_replace($p,$r,$v);
	return $t;
}*/

# Число прописью
function num_propis($num){ // $num - цело число

# Все варианты написания чисел прописью от 0 до 999 скомпануем в один небольшой массив
 $m=array(
  array('нуль'),
  array('-','один','два','три','чотири',"п’ять",'шість','сім','вісім','дев’ять'),
  array('десять','одинадцять','дванадцять','тринадцять','чотирнадцять',"п’ятнадцять",'шістнадцять','сімнадцять','вісімнадцять','дев’ятнадцять'),
  array('-','-','двадцять','тридцять','сорок',"п’ятдесят",'шістдесят','сімдесят','вісімдесят',"дев’яносто"),
  array('-','сто','двісті','триста','чотириста',"п’ятсот",'шістсот','сімсот','вісімсот',"дев’ятсот"),
  array('-','одна','дві')
 );

# Все варианты написания разрядов прописью скомпануем в один небольшой массив
 $r=array(
  array('...ліон','','и','ів'), // используется для всех неизвестно больших разрядов 
  array('тисяч','а','і',''),
  array('мільйон','','и','ів'),
  array('мільярд','','и','ів'),
  array('трильйон','','и','ів'),
  array('квадриліон','','и','ів'),
  array('квинтиліон','','и','ів')
  // ,array(... список можно продолжить
 );

 if($num==0)return$m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
 $o=array(); # Сюда записываем все получаемые результаты преобразования

# Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
 foreach(array_reverse(str_split(str_pad($num,ceil(strlen($num)/3)*3,'0',STR_PAD_LEFT),3))as$k=>$p){
  $o[$k]=array();

#Алгоритм, преобразующий трехзначное число в строку прописью
  foreach($n=str_split($p)as$kk=>$pp)
  if(!$pp)continue;else
   switch($kk){
    case 0:$o[$k][]=$m[4][$pp];break;
    case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
    case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
   }$p*=1;if(!$r[$k])$r[$k]=reset($r);

# Алгоритм, добавляющий разряд, учитывающий окончание руского языка
  if($p&&$k)switch(true){
   case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
   case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
   default:$o[$k][]=$r[$k][0].$r[$k][3];break;
  }$o[$k]=implode(' ',$o[$k]);
 }
 
 return implode(' ',array_reverse($o));
}
function mb_ucfirst($str, $enc = 'utf-8') { 
	return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
}
function str_lreplace($search, $replace, $subject){
    $pos = strrpos($subject, $search);
    if($pos !== false)    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
function prep_rating_array($ids)
{
	$a_ids = explode(',',$ids);
	$i = -1;
	$a = array();
	while(++$i<count($a_ids))
	{
		if (array_key_exists($a_ids[$i],$a) || $a_ids[$i] == '') continue;
		$a[$a_ids[$i]] = array('name'=>'','games'=>0,'winner'=>0,'bm'=>0,'summ'=>0,'dops'=>0,'mir'=>0,'maf'=>0,'don'=>0,'sherif'=>0);
	}
	return $a;
}
function calc_rating(&$p,&$v,&$a)
{
	$i=-1;
	while(++$i<count($p))
	{
		if ($a[$p[$i]['id']]['name'] === '')
			$a[$p[$i]['id']]['name'] = $p[$i]['name'];
		++$a[$p[$i]['id']]['games'];
		$a[$p[$i]['id']]['winner'] += ($v['win'] === 1 && ($p[$i]['role'] === 0 || $p[$i]['role'] === 4) || $v['win'] === 2 && ($p[$i]['role'] === 1 || $p[$i]['role'] === 2) ? 1 : 0);
		$a[$p[$i]['id']]['bm'] += ($i === $v['make_bm'] && count($v['bm']) > 0 || $p[$i]['role'] === 4) ? $this->check_bm($v['bm'],$p) : 0;
		$a[$p[$i]['id']]['summ'] += $p[$i]['points'];
		$a[$p[$i]['id']]['dops'] += isset($v['dops'][$p[$i]['id']]) ? $v['dops'][$p[$i]['id']] : 0.0;
		$a[$p[$i]['id']]['mir'] += ($p[$i]['role'] === 0 || $p[$i]['role'] === 4 ? 1 : 0);
		$a[$p[$i]['id']]['maf'] += ($p[$i]['role'] === 1 || $p[$i]['role'] === 2 ? 1 : 0);
		$a[$p[$i]['id']]['don'] += ($p[$i]['role'] === 2 ? 1 : 0);
		$a[$p[$i]['id']]['she'] += ($p[$i]['role'] === 4 ? 1 : 0);
		$a[$p[$i]['id']]['win_mir'] += ($v['win'] === 1 && ($p[$i]['role'] === 0 || $p[$i]['role'] === 4) ? 1 : 0);
		$a[$p[$i]['id']]['win_maf'] += ($v['win'] === 2 && ($p[$i]['role'] === 1 || $p[$i]['role'] === 2) ? 1 : 0);
		$a[$p[$i]['id']]['win_don'] += ($v['win'] === 2 &&($p[$i]['role'] === 2) ? 1 : 0);
		$a[$p[$i]['id']]['win_she'] += ($v['win'] === 1 && ($p[$i]['role'] === 4) ? 1 : 0);
		$a[$p[$i]['id']]['q_win'] = $a[$p[$i]['id']]['winner'] !== 0 ? ($a[$p[$i]['id']]['games'] / $a[$p[$i]['id']]['winner']) * 100 : 0;
	}
}
function convert_to_string(&$a,$sep=',')
{
	$s = '';
	foreach($a as $k=>$v)
	{
		if (is_array($v)) $s .= convert_to_string($v,$sep);
		else $s .= $v.',';
	}
	return str_replace($sep.$sep,$sep,$s);
}