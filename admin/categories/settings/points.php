<?$a_settings = $engine->settingsGet(array('id','name','value'), 'point')?>
	<div class="points-table">
		<?for($x=0;$x<count($a_settings);$x++):?>
			<div class="points-table__cell" data-action-type="edit-points" data-points-id="<?=$a_settings[$x]['id']?>">
				<label class="points-table__cell-name">
					<?=$a_settings[$x]['name']?>
				</label>
				<span class="points-table__cell-value">
					<?=str_replace(',',', ',$a_settings[$x]['value'])?>
				</span>
				<?=$engine->checkAndPutImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
			</div>
		<?endfor?>
	</div>