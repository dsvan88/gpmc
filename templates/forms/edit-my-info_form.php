<?
$user_data = $engine->GetGamerData(array($_POST['column']),array('id'=>$_SESSION['id'])); 
$output['html'] .= '
	<form class="edit-info-row">
		<h2 class="title">Изменение данных</h2>
		<div class ="input_row">
		<label>Новое значение</label>';
if ($_POST['column'] !== "gender"){
	if ($_POST['column'] === "birthday")
		$output['html'] .= '<input name="value" type="text" class="input_gamer datepick" value ="'.date("d.m.Y",$user_data[$_POST['column']]).'"/>';
	else
		$output['html'] .= '<input name="value" type="text" class="input_gamer" value ="'.$user_data[$_POST['column']].'"/>';
}
else {
	$genders = array("Инкогнито","Господин","Госпожа","Некто");
	$output['html'] .= '<select name="value" class="select_gamer">';
	for ($x=0;$x<count($genders);$x++)
		$output['html'] .= '<option value="'.$x.'"'.($user_data["gender"] == $x ? " selected" : "").'>'.$genders[$x].'</option>';
	$output['html'] .= '</select>';
}
$output['html'] .= '
		</div>
		<input name="column" type="hidden" value ="'.$_POST['column'].'"/>
		<div class ="input_row buttons">
			<button>Изменить</button>
		</div>
		<span>* Введите новые данные.</span>
	</form>';