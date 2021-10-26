   
<?php

require_once dirname(__FILE__) . '/../constant.php';

class Util
{
	public function getHash($str, $extra) {
		return sha1($str.$extra);
	}
}