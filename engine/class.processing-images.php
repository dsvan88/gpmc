<?
class ImageProcessing {
    function imageToWebp($source,$output,$from='png'){
		$func = 'imagecreatefrom'.($from !== 'jpg' ? $from : 'jpeg');
		$image = $func($source);
		if ($from === 'png'){
			imagepalettetotruecolor($image);
			imagealphablending($image, true);
			imagesavealpha($image, true);
		}
		imagewebp($image,$output);
		imagedestroy($image);
	}
	function getAdditionalImage($source, $format, $type='webp'){
		$output = '';
		if ($type === 'webp'){
			$webp = str_replace(".$format",'.webp', $source);
			if (!file_exists($webp))
				$this->imageToWebp($source,$webp,$format);
			$output .= PHP_EOL.'<source srcset="'.$webp.'" type="'.mime_content_type($webp).'">';
		}
		elseif ($type === 'mini'){
			$mini = substr($source,0,strrpos($source,'.')).'-mini.'.$format;

			if (!file_exists($mini)) return '';
			if ($format !== 'webp'){
				$webp = str_replace(".$format",'.webp', $mini);
				if (!file_exists($webp))
					$this->imageToWebp($mini,$webp,$format);
				$output .= PHP_EOL.'<source srcset="'.$webp.'" media="(max-width: 576px)" type="'.mime_content_type($webp).'">';
			}
			$output .= PHP_EOL.'<source srcset="'.$mini.'" media="(max-width: 576px)" type="'.mime_content_type($mini).'">';
		}
		return PHP_EOL.$output;
	}
	function checkAndPutImage($source,$options=[])
	{
		$output = '<picture>';
		$realPathToSource = $_SERVER['DOCUMENT_ROOT'].$source;
		$format = str_replace('image/','',mime_content_type($realPathToSource));
		
		$output .= $this->getAdditionalImage($realPathToSource,$format,'mini');

		if ($format !== 'webp')
			$output .= $this->getAdditionalImage($realPathToSource,$format,'webp');
		
		$attrs = '';
		foreach ($options as $attr=>$value){
			if ($attr !== 'title'){
				$attrs .= "$attr='$value' ";
			}
			else{
				$attrs .= "$attr='$value' alt='$value' ";
			}
		}
		return str_ireplace($_SERVER['DOCUMENT_ROOT'],'.', $output.PHP_EOL.
			'<img '.$attrs.' src="'.$source.'">
		 </picture>');
	}
}