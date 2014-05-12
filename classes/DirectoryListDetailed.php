<?php

class DirectoryListDetailed extends DirectoryList
{
	/**
	 * @var string The HTML text that makes up the path navigation links
	 */
	protected $path_nav;
	
	/**
	 * @var int Total number of files in this directory
	 */
	protected $total_files;
	
	/**
	 * @var int Total number of folders in this directory
	 */
	protected $total_folders;
	
	/**
	 * @var int Total number of folders in this directory (including parent)
	 */
	protected $raw_total_folders;
	
	/**
	 * @return string The HTML text that makes up the path navigation
	 */
	private function set_path_nav()
	{
		global $config, $subdir;
		$exploded = explode('/', $subdir);
		$c = count($exploded) - 1;
		$temp = '<a href="' . Url::html_output($_SERVER['PHP_SELF']) . '?dir=">Downloads </a>';
		for ($i = 0; $i < $c; $i++)
		{
			$temp .= '<a href="' . Url::html_output($_SERVER['PHP_SELF'])
			. '?dir=';
			for ($j = 0; $j <= $i; $j++)
			{
				$temp .= Url::translate_uri($exploded[$j]) . '/';
			}
			$temp .= '">' . Url::html_output($exploded[$i]) . '</a> / ';
		}
		return $temp;
	}
	
	/**
	 * @return int The total number of files and folders (including the parent folder)
	 */
	public function total_items()
	{
		return $this -> raw_total_folders + $this -> total_files;
	}
	
	/**
	 * @param string $path The directory to read the files from
	 * @param int $page The number of files to skip (used for pagination)
	 */
	public function __construct($path, $page = 1)
	{
		$path = Item::make_sure_slash($path);
		parent::__construct($path);
		$subtract_parent = false;
		$dirs = $files = array();
		foreach ($this as $t)
		{
			if (@is_dir($path . $t))
			{
				$temp = new DirItem($path, $t);
				if ($temp -> __get('is_parent_dir'))
				{
					$dirs[] = $temp;
					$subtract_parent = true;
				}
				else if ($temp -> __get('filename') !== false)
				{
					$dirs[] = $temp;
				}
			}
			else if (@is_file($path . $t))
			{
				$temp = new FileItem($path, $t);
				if ($temp -> __get('filename') !== false)
				{
					$files[] = $temp;
				}
			}
		}
		$this -> contents = array_merge($dirs, $files);
		$this -> total_files = count($files);
		$this -> raw_total_folders = $this -> total_folders = count($dirs);
		if ($subtract_parent)
		{
			$this -> total_folders--;
		}
		$this -> path_nav = $this -> set_path_nav();
	}
	
	public function display()
	{
        global $config;
		include(PATH_TO_TEMPLATES . 'table_header.tpl');
		include(PATH_TO_TEMPLATES . 'each_file.tpl');
		include(PATH_TO_TEMPLATES . 'table_footer.tpl');
	}
}
