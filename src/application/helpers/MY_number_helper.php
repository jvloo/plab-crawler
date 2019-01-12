<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 if ( ! function_exists('number_limiter')) {
	 	function number_limiter($int, int $limit = 99, string $overflow_char = '+') {
      $int = (int) trim($int);
			if ($int >= $limit) {
				return $limit.$overflow_char;
			}
			return $int;
		}
 }
