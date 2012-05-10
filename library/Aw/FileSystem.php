<?php
class Aw_FileSystem {
	public static function rmdir($dir) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($iterator as $path) {
			if ($path->isDir()) {
				rmdir($path->getPath());
			} else {
				unlink($path->__toString());
			}
		}
	}
}