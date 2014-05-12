<?php
error_reporting(E_ALL | E_NOTICE);

$config = array(
    'base_dir' => 'd0wnLoadS',
    'icon_path' => 'index_icons/',
    'days_new' => 3,
    );

//paths for files that will be included
define('PATH_TO_CLASSES', 'classes/');

//filenames of template files
define('PATH_TO_TEMPLATES', 'templates/');

/**
 * Format to display dates in.
 * @see date()
 */
define('DATE_FORMAT', 'j F Y');

define('SECONDS_NEW',  10  );

/**
 * Formats $text within valid XHTML 1.1 tags and doctype.
 *
 * @param string $text
 * @param string $title
 * @return string
 */
function simple_display($text, $title = 'Error on Page')
{
	return '<!DOCTYPE html>
<html>
<head>
	<title>' . $title . '</title>
	<style>
		html, body
		{
			font-family: verdana, lucidia, sans-serif;
			font-size: 13px;
			background-color: #F0F0F0;
			color: #000000;
		}
	</style>
</head>
<body>
<p>' . $text . '</p></body></html>';
}

function __autoload($class)
{
	if ($class != 'self') require_once(PATH_TO_CLASSES . $class . '.php');
}

class ExceptionFatal extends Exception {}

try
{
	include(CONFIG_STORED);
	
	/* From this point on, we can throw ExceptionDisplay rather than
	 * Exception since all the configuration is done.
	 */	
	$dir = $config['base_dir'];
	$subdir = '';

    if (isset($_POST['file'])) {
        $dir = dirname($_POST['file']);
        $dir = Url::clean_input($dir);
        $dir = Item::make_sure_slash($dir);
        $filename = basename($_POST['file']);
        $filename = Url::clean_input($filename);
        if (!@is_file($dir . $filename)) die('{}');
        $item = new FileItem($dir, $filename);
        die('{"is_being_written": ' . ($item -> is_being_written ? 'true' : 'false') . ', "last_mod": "' . $item -> format_m_time() . '", "size": "' . $item -> size . '"}');
    }
	
	if (isset($_GET['dir']))
	{
		$dir .= Url::clean_input($_GET['dir']);
		$dir = Item::make_sure_slash($dir);
		if (!@is_dir($dir))
		{
			header('HTTP/1.0 404 Not Found');
			$_GET['dir'] = ''; //so the "continue" link will work
			throw new ExceptionDisplay('The directory <em>'
			. Url::html_output($dir) . '</em> does not exist.');
		}
		$subdir = substr($dir, strlen($config['base_dir']));
	}
	
	if (isset($_GET['search']) && $_GET['search'] != '')
	{
		$s = Url::clean_input($_GET['search']);
		$dir_list = new Search($s, $dir);
	}
	else
	{
		$dir_list = new DirectoryListDetailed($dir);
	}
	
	require(PATH_TO_TEMPLATES . 'global_header.tpl');
	$dir_list -> display();
	require(PATH_TO_TEMPLATES . 'global_footer.tpl');
}
catch (ExceptionDisplay $e)
{
	echo $e;
}
catch (Exception $e)
{
	echo simple_display($e -> getMessage());
}
