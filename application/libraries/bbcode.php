<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Bbcode {

	function __construct() {
		// code...
	}

	public function toHTML( $string = '' ) {
		$search = array(
			"@\r\n@",
			"@\[size=([\w%]*)\](.*?)\[\/size\]@",
			"@\[b\](.*?)\[\/b\]@",
			"@\[i\](.*?)\[\/i\]@",
			"@\[u\](.*?)\[\/u\]@",
			"@\[img\](.*?)\[\/img\]@",
			"@\[img=(\d*),0\](.*?)\[\/img\]@",
			"@\[url\=(.*?)\](.*?)\[\/url\]@",
			"@\[code\](.*?)\[\/code\]@",
			"@\[color=([\w#]*)\](.*?)\[\/color\]@",
			"@\[hr\]@",
			"@\[align=([\w]*)\](.*?)\[\/align\]@",
			"@\[quote\](.*?)\[\/quote\]@",
		);
		$replace = array(
			"<br />",
			"<span style='font-size: \\1'>\\2</span>",
			"<b>\\1</b>",
			"<i>\\1</i>",
			"<u>\\1</u>",
			"<img src='\\1' alt='>",
			"<img width='\\1' src='\\2' alt=''>",
			"<a href='\\1'>\\2</a>",
			"<code>\\1</code>",
			"<span style='color: \\1'>\\2</span>",
			"<hr />",
			"<div align='\\1'>\\2</div>",
			"<blockquote>\\1</blockquote>",
		);

		$return = preg_replace($search, $replace, $string);

		return $return;
	}
}

//
