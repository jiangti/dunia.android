<?php
class Aw_Helper_Thumbnail extends Zend_View_Helper_Abstract {
	/**
	 * $this->thumbnail($absolutePath, $width, $height, $cachePath = null);
	 * /public/cache/*****.jpg
	 * @param unknown_type $fullPath
	 * @param unknown_type $width
	 * @param unknown_type $height
	 */
	public function thumbnail($fullPath, $width, $height, $cachePath = null) {
		if (empty($cachePath) && !Zend_Registry::isRegistered('AW_THUMBNAIL_CACHE')){
			throw new Exception('A cache path that is publicly accessible is needed.');
		}

		$cacheFolder = Zend_Registry::get('AW_THUMBNAIL_CACHE');
		$fileName = md5($fullPath) . '.png';
		$absolute = $cacheFolder . '/' . $fileName;

		/**
		 * Make the thumbnail.
		 */
		require_once AW_ROOT . '/Contrib/PhpThumb/phpthumb.class.php';

		$thumb = new phpThumb();
		$thumb->setCacheDirectory($cacheFolder);
		$thumb->setSourceFilename($fullPath);
		$thumb->setOutputFormat('png');
		$thumb->setParameter('w', $width);
		$thumb->setParameter('h', $height);


		$thumb->generateThumbnail();
		$thumb->renderToFile($absolute);
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $absolute);
		/**
		 * Serves the endpoint of the cached file.
		 */
	}
}