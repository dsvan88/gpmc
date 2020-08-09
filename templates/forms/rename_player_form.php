<form id='RenameEveningPlayer'>
	<input type='hidden' name='old_name' value='<?=$_POST['n']?>'/>
	<div class ='input_row'><input name="new_name" type="text" class="input_gamer" value =""/></div>
	<div class ='input_row'><button id='RenamePlayer'>Переименовать</button></div>
	<span>* Введите новый игровой ник игрока.
	Он не должен совпадать с уже существующими</span>
</form>