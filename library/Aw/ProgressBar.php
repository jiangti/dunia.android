<?php
class Aw_ProgressBar extends Zend_ProgressBar
{
	public function next($diff = 1, $text = null) {

		if ($text === null) {
			$text = sprintf("MEM %sM", memory_get_peak_usage(1) / 1024 / 1024);
		} else {
			$text = sprintf("MEM %sM %s", memory_get_peak_usage(1) / 1024 / 1024, $text);
		}

		parent::next($diff, $text);
	}
}