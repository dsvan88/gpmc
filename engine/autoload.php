<?
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/functions.php';

[$template,$settings,$evening,$places,$users,$images, $news] = engineStart();

$genders=['','господин','госпожа','некто'];
$statuses = ['Гость', 'Резидент', 'Мастер'];
$userData['status'] = 'guest';

// $eveningsBooked = $evening->nearEveningGetData(['id','date','place','games','participants','participants_info']);
$eveningsBooked = $evening->eveningsGetBooked();
if ($eveningsBooked){
	// print_r($eveningsBooked);
	for ($i=0; $i < count($eveningsBooked); $i++) { 

		if (isset($eveningsBooked[$i]['place']) && is_numeric($eveningsBooked[$i]['place'])){
			$eveningsBooked[$i]['place'] = $places->placeGetDataByID($eveningsBooked[$i]['place']);
		}

		if (isset($eveningsBooked[$i]['participants_info']))
			$eveningsBooked[$i]['participants_info'] = json_decode($eveningsBooked[$i]['participants_info'],true);
		if (isset($eveningsBooked[$i]['start']) && $eveningsBooked[$i]['start'] && count($eveningsBooked[$i]['participants_info']) === 0){
			$randomUsers = $users->usersGetRandomNames(13);
			for ($x=0; $x < count($randomUsers); $x++) { 
				$eveningsBooked[$i]['participants_info'][$x] = [
					'name' => $randomUsers[$x],
					'arrive' => '',
					'duration' => 0
				];
			}
		}
	}
}
$settingsArray = $settings->modifySettingsArray($settings->settingsGet(['short_name','name','value','type'],['img','txt']));

$gendersImages = [
	'none' => $settingsArray['img']['profile']['value'],
	'male' => $settingsArray['img']['male']['value'],
	'female' => $settingsArray['img']['female']['value'],
	'unknow' => $settingsArray['img']['profile']['value']
];