<?php

class Search extends DirectoryListDetailed
{
	/**
	 * @var array List of matched filenames
	 */
	private $matches;
	
	/**
	 * @return string The HTML text that makes up the search box
	 */
	public static function search_box()
	{
		global $words, $subdir;
		$search = (isset($_GET['search']) ? Url::html_output($_GET['search']) : '');
		$out = '<form action="' . Url::html_output($_SERVER['PHP_SELF']) . '" method="get">'
		. '<p><input type="hidden" name="dir" value="' . $subdir . '" />'
		. '<input type="text" name="search" value="' . $search
		. '" /><br /><input type="submit" class="button" value="Rechercher" /></p></form>';
		return $out;
	}
	
	/**
	 * @param string $filename
	 * @param string $string
	 * @return bool True if string matches filename
	 */
	private static function match(&$filename, &$string)
	{
		if (preg_match_all('/(?<=")[^"]+(?=")|[^ "]+/', $string, $matches))
		{
			foreach ($matches[0] as $w)
			{
				if (stripos($filename, $w) !== false)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Returns a string with all characters except 'd' and 'f' stripped.
	 * Either 'd' 'f' 'df' will be returned, defaults to 'f'
	 *
	 * @param string $mode
	 * @return string
	 */
	private static function clean_mode($mode)
	{
		$str = '';
		if (stripos($mode, 'f') !== false)
		{
			$str .= 'f';
		}
		if (stripos($mode, 'd') !== false)
		{
			$str .= 'd';
		}
		else if ($str == '')
		{
			$str = 'f';
		}
		return $str;
	}
	
	/**
	 * @param string $query String to search for
	 * @param string $dir The folder to search (recursive)
	 */
	public function __construct($query, $dir)
	{
		if (strlen($query) < 2 || strlen($query) > 20)
		{
			throw new ExceptionDisplay('La chaîne recherchée est trop courte (ou trop longue).');
		}
		$dir = Item::make_sure_slash($dir);
		DirectoryList::__construct($dir);
		$this -> matches = array();
		$this -> total_size = new Size(0);
		$this -> total_downloads = $this -> total_folders = $this -> total_files = 0;
		foreach ($this as $item)
		{
			if ($item == '..')
			{
				continue;
			}
			if (@is_dir($dir . $item))
			{
				$sub_search = new Search($query, $dir . $item);
				$this -> matches = array_merge($this -> matches, $sub_search -> contents);
			}
			else if (self::match($item, $query))
			{
				$this -> matches[] = new FileItem($dir, $item);
			}
		}
		global $config, $subdir;
		$link = ' <a href="' . Url::html_output($_SERVER['PHP_SELF'])
		. '?dir=' . Url::translate_uri($subdir) . '">'
		. Url::html_output($dir) . '</a> ';
		$this -> path_nav = 'Résultat de recherche pour ' . $link . ' et ses sous-dossiers';
		$this -> contents = $this -> matches;
		unset($this -> matches);
	}
}
