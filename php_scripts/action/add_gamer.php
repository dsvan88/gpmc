<?
$id = $engine->GetGamerID($_POST['n']);
$engine->AddGamerToEvening($engine->GetEveningID(strtotime(date('d.m.Y'))),$id);
echo $id;