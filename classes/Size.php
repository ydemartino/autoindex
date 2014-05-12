<?php

class Size
{
	/**
	 * @var int Size in bytes
	 */
	private $bytes;
	
	/**
	 * @return string Returns $bytes formatted as a string
	 */
	public function formatted()
	{
		$size = $this -> bytes;
		if ($size === true)
		//used for the parent directory
		{
			return '&nbsp;';
		}
		if ($size === false)
		//used for regular directories (if SHOW_DIR_SIZE is false)
		{
			return '-&nbsp;';
		}
		static $u = array('o', 'Ko', 'Mo', 'Go');
		for ($i = 0; $size >= 1024 && $i < 4; $i++)
		{
			$size /= 1024;
		}
		return number_format($size, min(1, $i)) . ' ' . $u[$i];
	}
	
	/**
	 * Adds the size of $s into $this
	 *
	 * @param Size $s
	 */
	public function add_size(Size $s)
	{
		$temp = $s -> __get('bytes');
		if (is_int($temp))
		{
			$this -> bytes += $temp;
		}
	}
	
	/**
	 * True if parent directory,
	 * False if directory,
	 * Integer for an actual size.
	 *
	 * @param mixed $bytes
	 */
	public function __construct($bytes)
	{
		$this -> bytes = ((is_bool($bytes)) ? $bytes : max((int)$bytes, 0));
	}
	
	/**
	 * @param string $var The key to look for
	 * @return string The value $name points to
	 */
	public function __get($var)
	{
		if (isset($this -> $var))
		{
			return $this -> $var;
		}
		throw new ExceptionDisplay('Variable <em>' . Url::html_output($var)
		. '</em> not set in Size class.');
	}
	
	public function __toString() 
	{
		return $this -> formatted();
	}
}
