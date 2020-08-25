<? $user_data = $engine->GetPlayerData(array($_POST['c']),array('id'=>$_SESSION['id'])); 
$output['html'] .= '
	<form id="Form_EditUserInfoRow">
		<div class ="input_row">';
if ($_POST["c"] !== "gender"){
	if ($_POST["c"] !== "birthday")
		$output['html'] .= '<input name="'.$_POST["c"].'" type="text" class="input_gamer datepick" value ="'.date("d.m.Y",$user_data[$_POST["c"]]).'"/>';
	else
		$output['html'] .= '<input name="'.$_POST["c"].'" type="text" class="input_gamer" value ="'.$user_data[$_POST["c"]].'"/>';
}
else {
	$genders = array("Инкогнито","Господин","Госпожа","Некто");
	$output['html'] .= '<select name="gender" class="select_gamer">';
	for ($x=0;$x<count($genders);$x++)
		$output['html'] .= '<option value="'.$x.'"'.($user_data["gender"] == $x ? " selected" : "").'><'.$genders[$x].'</option>';
	$output['html'] .= '</select>';
}
$output['html'] .= '
		</div>
		<div class ="input_row">
			<button id="EditUserInfoRow">Изменить</button>
		</div>
		<span>* Введите новые данные.</span>
	</form>';