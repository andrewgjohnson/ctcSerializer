<?php

/*
 * ctcSerializer v1.0.0
 *
 * Copyright (c) 2013 Andrew G. Johnson  <andrew@andrewgjohnson.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @copyright Copyright (c) 2013 Andrew G. Johnson <andrew@andrewgjohnson.com>
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
		private static $default_settings = array(
			'addDecl' => false,
			'defaultTagName' => 'container',
			'encoding' => 'utf-8',
			'rootName' => 'ctcSerializer'
		);

		public static function serialize($data = '',$settings = array())
		{
			if (is_array($settings))
			{
				$settings = array_merge(self::$default_settings,$settings);
				$settings['defaultTagName'] = self::get_element_name($settings['defaultTagName'],self::$default_settings['defaultTagName']);
				$settings['encoding'] = trim((string)$settings['encoding']);
				$settings['rootName'] = self::get_element_name($settings['rootName'],self::$default_settings['rootName']);
			}
			else
				$settings = self::$default_settings;

			$serialized  = '';
			if ($settings['addDecl'] === true)
				$serialized .= '<?xml version="1.0" ' . (strlen($settings['encoding']) > 0 ? 'encoding="' . htmlspecialchars($settings['encoding'],ENT_QUOTES) . '" ' : '') . '?>';
			$serialized .= '<' . $settings['rootName'] . '>';
			$serialized .= self::serialize_data($data,$settings);
			$serialized .= '</' . $settings['rootName'] . '>';
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
						$serialized .= '<' . self::get_element_name($key,$settings['defaultTagName']) . '>';
						$serialized .= self::serialize_data($value,$settings);
						$serialized .= '</' . self::get_element_name($key,$settings['defaultTagName']) . '>';
					}
					return $serialized;
				case 'boolean':
				case 'integer':
				case 'double':
				case 'string':
				case 'NULL':
					return htmlspecialchars((string)$data,ENT_QUOTES);
				case 'resource':
					return 'resource';
				default:
					return '';
			}
		}

		private static function get_element_name($element_name = '',$fallback_element_name = '')
		{
			$element_name = (string)$element_name;
			if (class_exists('DOMElement'))
			{
				try
				{
					new DOMElement($element_name);
					return $element_name;
				}
				catch (Exception $e)
				{
					return $fallback_element_name;
				}
			}
			else if (preg_match('/\A(?!XML)[a-z][\w0-9-]*$/i',$element_name))
				return $element_name;
			else
				return $fallback_element_name;
		}
	}
}