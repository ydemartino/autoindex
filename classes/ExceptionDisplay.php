<?php

class ExceptionDisplay extends ExceptionFatal
{
	/**
	 * @return string The HTML text to display
	 */
	public function __toString()
	{
		$str = '<table><tr><td style="padding: 8px;">'
		. $this -> message . '<p><a href="'
		. Url::html_output($_SERVER['PHP_SELF']);
		if (isset($_GET['dir']))
		{
			$str .= '?dir=' . Url::translate_uri($_GET['dir']);
		}
		$str .= '">Continue.</a></p></td></tr></table>';
		return $str;
	}
}
