<?php

abstract class Item
{
	/**
	 * @var string
	 */
	protected $filename;
	
	/**
	 * @var Size
	 */
	protected $size;
	
	/**
	 * @var int Last modified time
	 */
	protected $m_time;
	
	/**
	 * @var int Last accessed time
	 */
	protected $a_time;
	
	/**
	 * @var string The HTML text of the link to the type icon
	 */
	protected $icon;
	
	/**
	 * @var string The HTML text of the "[New]" icon
	 */
	protected $new_icon;

    protected $is_being_written;
	
	/**
	 * @var string The HTML text of the link to this file or folder
	 */
	protected $link;
	
	/**
	 * @var string The name and path of the parent directory
	 */
	protected $parent_dir;
	
	/**
	 * @var bool True if this is a link to '../'
	 */
	protected $is_parent_dir;
	
	/**
	 * @param int $timestamp Time in UNIX timestamp format
	 * @return string Formatted version of $timestamp
	 */
	private static function format_date($timestamp)
	{
		if ($timestamp === false)
		{
			return '&nbsp;';
		}
		return date(DATE_FORMAT, $timestamp);
	}
	
	/**
	 * @return string Date modified (m_time) formatted as a string
	 * @see Item::format_date()
	 */
	public function format_m_time()
	{
		return self::format_date($this -> m_time);
	}
	
	/**
	 * @return string Date last accessed (a_time) formatted as a string
	 * @see Item::format_date()
	 */
	public function format_a_time()
	{
		return self::format_date($this -> a_time);
	}
	
	/**
	 * Returns everything after the slash, or the original string if there is
	 * no slash. A slash at the last character of the string is ignored.
	 *
	 * @param string $fn The file or folder name
	 * @return string The basename of $fn
	 * @see basename()
	 */
	public static function get_basename($fn)
	{
		return basename(str_replace('\\', '/', $fn));
	}
	
	/**
	 * @param string $path The directory name
	 * @return string If there is no slash at the end of $path, one will be added
	 */
	public static function make_sure_slash($path)
	{
		$path = str_replace('\\', '/', $path);
		if (!preg_match('#/$#', $path))
		{
			$path .= '/';
		}
		return $path;
	}
	
	/**
	 * @param string $parent_dir
	 * @param string $filename
	 */
	public function __construct($parent_dir, $filename)
	{
		$parent_dir = self::make_sure_slash($parent_dir);
		$full_name = $parent_dir . $filename;
		$this -> is_parent_dir = false;
		$this -> m_time = filemtime($full_name);
		$this -> a_time = fileatime($full_name);
		$this -> icon = $this -> new_icon = $this -> thumb_link = '';
		$this -> parent_dir = $parent_dir;
        $this -> is_being_written = time() - $this-> m_time <= SECONDS_NEW;
		global $config;
		$days_new = $config['days_new'];
		$age = (time() - $this -> m_time) / 86400;
		$age_r = round($age, 1);
		$s = (($age_r == 1) ? '' : 's');
		
		$this -> new_icon = (($days_new > 0 && $age <= $days_new) ?
		' <img src="' . $config['icon_path'] . 'new.gif" alt="Ajouté il y a ' . "$age_r jour$s" . '" />' : '');
	}
	
	/**
	 * @param string $var The key to look for
	 * @return bool True if $var is set
	 */
	public function is_set($var)
	{
		return isset($this -> $var);
	}
	
	/**
	 * @return string The file or folder name
	 */
	public function __toString()
	{
		return $this -> filename;
	}
	
	/**
	 * @return string The file extension of the file or folder name
	 */
	abstract public function file_ext();
}
