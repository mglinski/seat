<?php
/**
 * Img.php
<<<<<<< HEAD
=======
 * Created by: matthewglinski
 * Date: 2/20/15 9:57 AM
>>>>>>> 85b67eacdd90beffd4a8b5b5c6ef983bcc3f9112
 */

namespace Seat\services\helpers;

/**
 * Class Img
 *
 * @package Seat\services\helpers
 */
class Img {

	/**
	 *
	 */
	const Character = 0;

	/**
	 *
	 */
	const Corporation = 1;

	/**
	 *
	 */
	const Alliance = 2;

	/**
	 *
	 */
	const Type = 3;

	/**
	 * @var array
	 */
	private static $types = array(
		0 => 'Character',
		1 => 'Corporation',
		2 => 'Alliance',
		3 => 'Type'
	);

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $attrs
	 * @return string
	 */
	public static function html($id, $size, $attrs) {

		if ($id > 90000000 && $id < 98000000) {
			return self::character($id, $size, $attrs);
		}
		elseif (($id > 98000000 && $id < 99000000) || ($id > 1000000 && $id < 2000000)) {
			return self::corporation($id, $size, $attrs);
		}
		elseif (($id > 99000000 && $id < 100000000) || ($id > 0 && $id < 1000000)) {
			return self::alliance($id, $size, $attrs);
		}
		else {
			return self::character($id, $size, $attrs);
		}
	}

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $attrs
	 * @return string
	 */
	public static function character($id, $size, $attrs) {
		return self::_renderHtml($id, $size, self::Character, $attrs);
	}

	/**
	 * @param $id
	 * @param $size
	 * @param $attrs
	 * @return string
	 */
	public static function corporation($id, $size, $attrs) {
		return self::_renderHtml($id, $size, self::Corporation, $attrs);
	}

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $attrs
	 * @return string
	 */
	public static function alliance($id, $size, $attrs) {
		return self::_renderHtml($id, $size, self::Alliance, $attrs);
	}

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $attrs
	 * @return string
	 */
	public static function type($id, $size, $attrs) {
		return self::_renderHtml($id, $size, self::Type, $attrs);
	}

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $type
	 * @param $attrs
	 * @return string
	 */
	public static function _renderHtml($id, $size, $type, $attrs) {

		// make new IMG tag
		$html = '<img ';

		// generate desired HTML attributes to properly lazy load
		$html .= 'src="' . \URL::asset('assets/img/bg.png') . '" ';
		$html .= 'data-src="' . self::_renderUrl($id, $size, $type) . '" ';
		$html .= 'data-src-retina="' . self::_renderUrl($id, ($size * 2), $type) . '" ';

		// unset already built attributes
		unset($attrs['src'], $attrs['data-src='], $attrs['data-src-retina']);

		// render other attributes
		foreach ($attrs as $name => $value) {
			$html .= "{$name}=\"{$value}\" ";
		}

		// close IMG tag
		$html .= ' />';

		// return completed img tag
		return $html;
	}

	/**
	 *
	 *
	 * @param $id
	 * @param $size
	 * @param $type
	 * @return string
	 */
	public static function _renderUrl($id, $size, $type) {

		// Base Eve Online Image CDN url
		$url = '//image.eveonline.com/';

		// construct ending bit of URL
		switch ($type) {
			case self::Corporation:
				$url .= self::$types[self::Corporation] . '/' . $id . '_' . $size . '.png';
				break;

			case self::Alliance:
				$url .= self::$types[self::Alliance] . '/' . $id . '_' . $size . '.png';
				break;

			case self::Type:
				$url .= self::$types[self::Type] . '/' . $id . '_' . $size . '.png';
				break;

			case self::Character:
			default:
				$url .= self::$types[self::Character] . '/' . $id . '_' . $size . '.jpg';
		}

		// return full URL
		return $url;
	}

}