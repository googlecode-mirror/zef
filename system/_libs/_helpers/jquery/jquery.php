<?php
/*
Name: jQuery 1.3.2 Helper
Author: Pierre Cornier
Description: Include all jQuery and jQueryUI necessary files.
Version: 1.0
Documentation: auto
*/


/*
<b>Description:</b>
Include the jQuery 1.3.2 necessary file.

<b>Parameters:</b>
echo: (TRUE or FALSE) echo or simply return the result.

<a href='http://docs.jquery.com/Main_Page'>jQuery Documentation</a>
*/
function jquery_required ( $echo = true ) {
	$html = "<script type=\"text/javascript\" src=\"" . BASEURL . "system/_libs/_helpers/jquery/jquery-1.3.2.min.js\"></script>\n";
	if ( !$echo ) return $html;
	echo $html;
}



/*
<b>Description:</b>
Include the jQuery UI necessary file.

<b>Parameters:</b>
echo: (TRUE or FALSE) echo or simply return the result.

<a href='http://docs.jquery.com/Main_Page'>jQuery Documentation</a>
*/
function jqueryui_required ( $echo = true ) {
	$html = "<script type=\"text/javascript\" src=\"" . BASEURL . "system/_libs/_helpers/jquery/jquery-1.3.2.min.js\"></script>\n";
	$html .= "<script type=\"text/javascript\" src=\"" . BASEURL . "system/_libs/_helpers/jquery/jquery-ui-1.7.1.custom.min.js\"></script>\n";
	$html .= "<link href=\"" . BASEURL . "system/_libs/_helpers/jquery/jquery-ui-1.7.1.custom.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	if ( !$echo ) return $html;
	echo $html;
}

?>