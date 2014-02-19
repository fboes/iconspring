<?php

class Iconspring {
	protected $filename;
	protected $outPath;
	protected $outNonWebPath;
	protected $outHtml = array();
	protected $outFileHtml = NULL;
	protected $outFileZip = NULL;
	protected $outFilesImage = array();

	/**
	 * [__construct description]
	 * @param [type] $filename [description]
	 * @param [type] $outPath  [description]
	 */
	public function __construct ($filename, $outPath, $outNonWebPath = NULL) {
		if (!file_exists($filename)) {
			throw new Exception('File not found');
		}
		else {
			$image = getimagesize($filename);
			if (!preg_match('#image/#',$image['mime'])){
				throw new Exception('File is not an image');
			}
		}
		if (!is_dir($outNonWebPath.$outPath)) {
			mkdir($outNonWebPath.$outPath);
		}
		$this->filename   = $filename;
		$this->outPath    = $outPath;
		$this->outNonWebPath = $outNonWebPath;
	}

	/**
	 * [build description]
	 * @param  [type] $filename [description]
	 * @param  [type] $rel      [description]
	 * @param  [type] $width    [description]
	 * @param  [type] $height   [description]
	 * @param  [type] $paddingX [description]
	 * @param  [type] $paddingY [description]
	 * @return bool             [description]
	 */
	public function build ($filename, $rel = 'icon', $width, $height = NULL, $paddingX = 0, $paddingY = NULL, $gravity = 'center') {
		$width = (int)$width;
		if ($height === NULL) {
			$height = $width;
		}
		$height = (int)$height;
		$paddingX = (int)$paddingX;
		if ($paddingY === NULL) {
			$paddingY = $paddingX;
		}
		$paddingY = (int)$paddingY;
		$filename = basename($filename);
		if ($this->convertImage($filename, $width, $height, $paddingX, $paddingY, $gravity)) {
			$this->addOutHtml($filename, $rel, $width, $height);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * [convertImage description]
	 * @param  string $filename [description]
	 * @param  int    $width    [description]
	 * @param  int    $height   [description]
	 * @param  int    $paddingX [description]
	 * @param  int    $paddingY [description]
	 * @param  string $gravity  [description]
	 * @return bool             [description]
	 */
	protected function convertImage ($filename, $width, $height, $paddingX, $paddingY, $gravity = 'center') {
		$enlarge = '^';
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$enlarge .= '^';
		}
		$cmd =
			'convert'
			.' -strip -interlace Plane -gaussian-blur 0.05 -quality 85%'
			.' '.escapeshellarg($this->filename)
			.' -resize '.((int)$width-(int)$paddingX).'x'.((int)$height-(int)$paddingY).$enlarge
			.' -gravity '.escapeshellarg($gravity)
			.' -background transparent'
			.' -extent '.((int)$width).'x'.((int)$height)
			.' '.escapeshellarg($this->outNonWebPath.$this->outPath.$filename)
		;
		if (defined('CONF_DEBUG') && CONF_DEBUG) {
			_print_r($cmd);
		}
		system($cmd, $retval);
		if ($retval >= 0) {
			$this->outFilesImage[] = $this->outPath.$filename;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * [addOutHtml description]
	 * @param [type] $filename [description]
	 * @param [type] $width    [description]
	 * @param [type] $height   [description]
	 */
	protected function addOutHtml ($filename, $rel, $width, $height) {
		if (empty($rel)) {
			$rel = 'icon';
		}
		$attributes = array(
			'rel' => $rel,
			'href' => basename($filename),
			'sizes' => (int)$width.'x'.(int)$height,
		);

		$html = '';
		foreach ($attributes as $name => $value) {
			$html .= ' '.$name.'="'.htmlspecialchars($value).'"';
		}
		$this->outHtml[] = '<link'.$html.' />';
	}

	/**
	 * [returnHtml description]
	 * @return [type] [description]
	 */
	public function returnHtml () {
		return implode("\n", $this->outHtml);
	}

	/**
	 * [saveHtml description]
	 * @param  string $filename [description]
	 * @return [type]           [description]
	 */
	public function saveHtml ($filename = 'meta.html') {
		if (file_put_contents($this->outPath.basename($filename), $this->returnHtml())) {
			$this->outFileHtml = $this->outPath.basename($filename);
			return TRUE;
		}
		return FALSE;
	}

	public function zip ($filename = 'icons.zip') {
		$oldDir = getcwd();
		$filename = $this->outPath.$filename;
		$zip = new ZipArchive();
		if ($zip->open($filename, ZipArchive::OVERWRITE) === TRUE) {
			chdir($this->outPath);
			$zip->addGlob('*.{ico,html,gif,png,jpg}', GLOB_BRACE, array('remove_all_path' => TRUE));
			chdir($oldDir);
			$zip->close();
			$this->outFileZip = $filename;
			return $filename;
		}
		return NULL;
	}
}