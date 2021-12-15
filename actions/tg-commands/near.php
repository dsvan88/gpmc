<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.evenings.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/class.places.php';

$evenings = new Evenings;
$places = new Places;

$eveningsBooked = $evenings->eveningsGetBooked();

if ($eveningsBooked){
    $output['message'] = '';
	for ($i=0; $i < count($eveningsBooked); $i++) { 

		if (isset($eveningsBooked[$i]['place']) && is_numeric($eveningsBooked[$i]['place'])){
			$eveningsBooked[$i]['place'] = $places->placeGetDataByID($eveningsBooked[$i]['place']);
		}

		if (isset($eveningsBooked[$i]['participants_info']))
			$eveningsBooked[$i]['participants_info'] = json_decode($eveningsBooked[$i]['participants_info'],true);
        
        $date = str_replace(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],['<b>Понедельник</b>','<b>Вторник</b>','<b>Среда</b>','<b>Четверг</b>','<b>Пятница</b>','<b>Суббота</b>','<b>Воскресенье</b>'],date('d.m.Y (l) H:i',$eveningsBooked[$i]['date']));
        $output['message'] .= "$date - {$eveningsBooked[$i]['game']}
{$eveningsBooked[$i]['place']['name']}
{$eveningsBooked[$i]['place']['info']}\r\n\r\n";
        for ($x=0; $x < count($eveningsBooked[$i]['participants_info']); $x++) { 
            $output['message'] .= ($x+1).". <b>{$eveningsBooked[$i]['participants_info'][$x]['name']}</b> {$eveningsBooked[$i]['participants_info'][$x]['arrive']}\r\n";
        }
        $output['message'] .= "____\r\n";
	}
}
else{
    $output['message'] = "Пока вечера игр не запланированны!\r\nПопробуйте позднее";
}
// $output['message'] = json_encode($eveningsBooked);


/* Четверг - Покер (обычный)
18:00 (70)
1. Паладин, 2. Хавер
_
Пятница - Мафия
_
Суббота - Мафия
1. Паладин
2. Зиппо
3. Пи
4. Хавер
5. Линда
_
Воскресенье - Покер Турнир */