<?
$def_vars =[
	'timer' => 6000,
	'stage'  => 'first_night',
	'prev_stage'  =>  '',
	'day_count'  =>  -1,
	'active'  =>  -1,
	'prev_active'  =>  -1,
	'kill'  =>  [[]],
	'last_will'  =>  [],
	'day_speaker'  =>  -1,
	'debater'  =>  -1,
	'b_bm' => false,
	'make_bm' => -1,
	'currentVote'  => [],
	'bm'  =>  [],
	'dops'  =>  [0=>0.0,1=>0.0,2=>0.0,3=>0.0,4=>0.0,5=>0.0,6=>0.0,7=>0.0,8=>0.0,9=>0.0,],
	'win'  =>  0
];
$reasons = ['','Убит','Осуждён','4 Фола','Дисквал.'];

$enum_roles = ['red','mafia','don','','sherif'];
$enum_roles_rus = ['Мирный','Мафия','Дон','','Шериф'];
$enum_rating = ['C','B','A'];