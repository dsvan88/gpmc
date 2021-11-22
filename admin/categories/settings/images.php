<?$a_settings = $engine->settingsGet(array('id','short_name','name','value'), 'img');?>
<div class='images-table'>
<?for($i=0;$i<count($a_settings);$i++):?>
	<div class='images-table__cell'>
		<h4 class='images-table__cell-caption'>
            <?=$a_settings[$i]['short_name']?>
        </h4>
		<div class='images-table__cell__image-place' data-form-type='edit-settings-image' data-edit-image='<?=$a_settings[$i]['id']?>'>
            <?=$engine->inputImage($a_settings[$i]['value'])?>
		</div>
		<?[$x, $y]= getimagesize($root_path.'/'.$a_settings[$i]['value'])?>
		<div class='images-table__cell__bottom'>
            <?=substr($a_settings[$i]['value'],strrpos($a_settings[$i]['value'],'/')+1)?>
            <br>
            <?=$x,'x',$y?>
        </div>
	</div>
<?endfor?>
</div>