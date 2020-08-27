<form id="EveningRegisterForm" method="POST">
<? if ($EveningData['ready'] === false)
	$EveningData = array(
			'date'=>strtotime(date('d.m.Y 17:00')),
			'place' => array(
				'name'=>'',
				'info' => ''
			),
			'gamers' => $engine->GetResidentsNames(11)
	);
	if ($EveningData['gamers'] === '')
		$EveningData['gamers'] = $engine->GetResidentsNames(11);
?>
<div id='EveningDate'><div>Время: </div><input class='datepick' type='text' name='eve_date' value='<?=date('d.m.Y H:i',$EveningData['date'])?>'/></div>
<div id='EveningPlace'><div>Место: </div><input type='text' name='eve_place' value='<?=$EveningData['place']['name']?>'/><br>
<div>Адрес: </div><input type='text' name='eve_place_info' value='<?=$EveningData['place']['info']?>' placeholder='Адрес, веб-сайт'/></div>
<div class='span_buttons_place'><span class='span_button' id='ApplyEvening'><img src='<?=$settings['img']['apply']['value']?>'/><?=isset($EveningData['id']) ? 'Изменить' : 'Подтвердить'?>!<img src='<?=$settings['img']['apply']['value']?>'/></span></div>
<hr>
<div id='GamerFields'>
Участники:
<?
	$i=-1;
	while(++$i<($EveningData['gamers'] !== '' ? count($EveningData['gamers']) : 11))
		include $root_path.'/templates/gamer_field.php';
	?>
	</div>
	<div class='span_buttons_place'>
		<span class='span_button' id='AddGamers'><img src='<?=$settings['img']['plus']['value']?>'/>Добавить поле<img src='<?=$settings['img']['plus']['value']?>'/></span><br><br>
		<span class='span_button' id='ApproveEvening'><img src='<?=$settings['img']['apply']['value']?>'/>Утвердить!<img src='<?=$settings['img']['apply']['value']?>'/></span>
	</div>
</form>