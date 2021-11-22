<?$a_settings = $engine->settingsGet(array('id','short_name','name','value'), 'txt')?>
	<div class="texts-table">
		<?for($x=0;$x<count($a_settings);$x++):?>
			<div class='texts-table__cell'>
				<h3 class='texts-table__cell__title'>
					<?=$a_settings[$x]['short_name']?>
					<a data-action-type="edit-setting-text" data-text-id="<?=$a_settings[$x]['id']?>">
						<?=$engine->inputImage($settings['img']['edit_pen']['value'],['title'=>$settings['img']['edit_pen']['name']])?>
					</a>
				</h3>
				<div class='texts-table__cell__content'>
					<?=str_replace(array('!BR!','«', '»'),array("\r\n",'"','"'),$a_settings[$x]['value'])?>
				</div>
			</div>
		<?endfor?>
	</div>