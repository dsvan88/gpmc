<?
$id = $engine->GetGamerID($_POST['n']);
$engine->AddGamerToEvening($engine->eveningGetId(strtotime(date('d.m.Y'))),$id);
echo $id;