<?php

class FileItem extends Item
{
	/**
	 * @param string $fn The filename
	 * @return string Everything after the list dot in the filename, not including the dot
	 */
	public static function ext($fn)
	{
		$fn = Item::get_basename($fn);
		return (strpos($fn, '.') ? strtolower(substr(strrchr($fn, '.'), 1)) : '');
	}
	
	/**
	 * @return string Returns the extension of the filename
	 * @see FileItem::ext()
	 */
	public function file_ext()
	{
		return self::ext($this -> filename);
	}
	
	/**
	 * @param string $parent_dir
	 * @param string $filename
	 */
	public function __construct($parent_dir, $filename)
	{
		parent::__construct($parent_dir, $filename);
		if (!@is_file($this -> parent_dir . $filename))
		{	
			throw new ExceptionDisplay('File <em>'
			. Url::html_output($this -> parent_dir . $filename)
			. '</em> does not exist.');
		}
		global $config, $words, $downloads;
		$this -> filename = $filename;
		$this -> size = new Size(filesize($this -> parent_dir . $filename));
		$file_icon = new Icon($filename);
		$this -> icon = $file_icon -> __toString();
		$this -> link = Url::html_output($_SERVER['PHP_SELF']) . '?dir=' . Url::translate_uri(substr($this -> parent_dir, strlen($config['base_dir'])))
		. '&amp;file=' . Url::translate_uri($filename);
		$size = $this -> size -> __get('bytes');
	}
	
	/**
	 * @param string $var The key to look for
	 * @return mixed The data stored at the key
	 */
	public function __get($var)
	{
		if (isset($this -> $var))
		{
			return $this -> $var;
		}
		throw new ExceptionDisplay('Variable <em>' . Url::html_output($var)
		. '</em> not set in FileItem class.');
	}
}
