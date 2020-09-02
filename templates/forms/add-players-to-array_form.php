<?
$output['html'] .= '
<form class="add-player-to-array-form">
	<h2 class="title">Добавление соучастника</h2>
	<div class ="input_row">
		<label>Псевдоним</label>
		<input name="gamer" type="text" class="input_name" value ="'.$players[$i].'"/></div>
	<div class ="input_row buttons">
		<button>Добавить</button>
	</div>
	<span>* Введите игровой ник игрока.</span>
</form>';