<?php

class Url
{
	/**
	 * @var string
	 */
	private $url;
	
	/**
	 * Rawurlencodes $uri, but not slashes.
	 *
	 * @param string $uri
	 * @return string
	 */
	public static function translate_uri($uri)
	{
		$uri = rawurlencode(str_replace('\\', '/', $uri));
		return str_replace(rawurlencode('/'), '/', $uri);
	}
	
	/**
	 * Returns the string with correct HTML entities so it can be displayed.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function html_output($str)
	{
		return htmlentities($str, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * Checks input for hidden files/folders, and deals with ".."
	 *
	 * @param string $d The URL to check
	 * @return string Safe version of $d
	 */
	private static function eval_dir($d)
	{
		$d = str_replace('\\', '/', $d);
		if ($d == '' || $d == '/')
		{
			return '';
		}
		$dirs = explode('/', $d);
		for ($i = 0; $i < count($dirs); $i++)
		{
			if (preg_match('/^\.\./', $dirs[$i])) //if it starts with two dots
			{
				array_splice($dirs, $i-1, 2);
				$i = -1;
			}
		}
		$new_dir = implode('/', $dirs);
		if ($new_dir == '' || $new_dir == '/')
		{
			return '';
		}
		if ($d{0} == '/' && $new_dir{0} != '/')
		{
			$new_dir = '/' . $new_dir;
		}
		if (preg_match('#/$#', $d) && !preg_match('#/$#', $new_dir))
		{
			$new_dir .= '/';
		}
		return $new_dir;
	}
	
	/**
	 * @param string $url The URL path to check and clean
	 * @return string Resolves $url's special chars and runs eval_dir on it
	 */
	public static function clean_input($url)
	{
		$url = rawurldecode( $url );
		$newURL = '';
		for ( $i = 0; $i < strlen( $url ); $i++ ) //loop to remove all null chars
		{
			if ( ord($url[$i]) != 0 )
			{
				$newURL .= $url[$i];
			}
		}
		return self::eval_dir( $newURL );
	}
	
	/**
	 * Sends the browser a header to redirect it to this URL.
	 */
	public function redirect()
	{
		$site = $this -> url;
		header("Location: $site");
		die(simple_display('Redirection header could not be sent.<br />'
		. "Continue here: <a href=\"$site\">$site</a>"));
	}
	
	/**
	 * Downloads the URL on the user's browser, using either the redirect()
	 * or force_download() functions.
	 */
	public function download()
	{
		$this -> redirect();
	}
	
	/**
	 * @param string $text_url The URL to create an object from
	 * @param bool $special_chars If true, translate_uri will be run on the url
	 */
	public function __construct($text_url, $special_chars = false)
	{
		if ($special_chars)
		{
			$text_url = self::translate_uri($text_url);
		}
		$this -> url = $text_url;
	}
	
	/**
	 * @return string Returns the URL as a string
	 */
	public function __toString()
	{
		return $this -> url;
	}
}