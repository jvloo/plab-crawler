<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('asset_url()') ) {
	function asset_url($uri = '') {
		$CI =& get_instance();
		return $CI->config->slash_item('asset_url').$uri;
	}
}

if ( ! function_exists('parse_link()') ) {
	function parse_link($url = '') {
		if ( ! empty($url) && strpos($url, 'http') === false ) {
			return base_url($url);
		}
		return $url;
	}
}

if ( ! function_exists('parse_source()') ) {
	function parse_source($url = '') {
		if ( ! empty($url) && strpos($url, 'http') === false ) {
			return asset_url($url);
		}
		return $url;
	}
}
