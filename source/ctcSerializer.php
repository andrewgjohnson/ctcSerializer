<?php

/*
 * ctcSerializer v1.0.0
 *
 * Copyright (c) 2013 Andrew G. Johnson andrew@andrewgjohnson.com
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @copyright Copyright (c) 2013 Andrew G. Johnson
 * @link http://github.com/ctcSerializer/ctcSerializer
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 1.0.0
 * @package ctcSerializer
 *
 */

if (!class_exists('ctcSerializer'))
{
	class ctcSerializer
	{
		static $default_settings = array(
			'addDecl' => false,
			'defaultTagName' => 'container',
			'encoding' => 'utf-8',
			'rootName' => 'ctcSerializer'
		);

		public static function serialize($data = '',$settings = array())
		{
			$settings = is_array($settings) ? array_merge(ctcSerializer::$default_settings,$settings) : ctcSerializer::$default_settings;

			$settings['rootName'] = ltrim((string)$settings['rootName'],'1234567890');
			if (strlen($settings['rootName']) == 0)
				$settings['rootName'] = ctcSerializer::$default_settings['rootName'];

			$settings['defaultTagName'] = ltrim((string)$settings['defaultTagName'],'1234567890');
			if (strlen($settings['defaultTagName']) == 0)
				$settings['defaultTagName'] = ctcSerializer::$default_settings['defaultTagName'];

			$serialized  = '';
			if ($settings['addDecl'] === true)
				$serialized .= '<?xml version="1.0" ' . (strlen($settings['encoding']) > 0 ? 'encoding="' . htmlspecialchars((string)$settings['encoding'],ENT_QUOTES) . '" ' : '') . '?>';
			$serialized .= '<' . ctcSerializer::get_element_name($settings['rootName'],$settings,true) . '>';
			$serialized .= ctcSerializer::serialize_data($data,$settings);
			$serialized .= '</' . ctcSerializer::get_element_name($settings['rootName'],$settings,true) . '>';
			return $serialized;
		}

		private static function serialize_data($data = '',$settings = array())
		{
			switch (gettype($data))
			{
				case 'object':
					$data = get_object_vars($data);
				case 'array':
					$serialized = '';
					foreach ($data as $key => $value)
					{
						$serialized .= '<' . ctcSerializer::get_element_name($key,$settings) . '>';
						$serialized .= ctcSerializer::serialize_data($value,$settings);
						$serialized .= '</' . ctcSerializer::get_element_name($key,$settings) . '>';
					}
					return $serialized;
				case 'boolean':
				case 'integer':
				case 'double':
				case 'string':
				case 'NULL':
					return ctcSerializer::get_element_value($data,$settings);
				case 'resource':
					return 'resource';
				case 'unknown type':
				default:
					return '';
			}
		}

		private static function get_element_name($tag_name = '',$settings = array(),$is_root_element = false)
		{
			$tag_name = ltrim((string)$tag_name,'1234567890');

			if (strlen($tag_name) > 0)
				return $tag_name;
			else if ($is_root_element === true)
				return $settings['rootName'];
			else
				return $settings['defaultTagName'];
		}

		private static function get_element_value($tag_value = '',$settings = array())
		{
			return htmlspecialchars((string)$tag_value,ENT_QUOTES);
		}
	}
}