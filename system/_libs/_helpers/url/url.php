<?php
/*
Name: URL
Author: Pierre Cornier
Description: URL helper.
Version: 1.0
Documentation: auto
*/


/*
<b>Description:</b>
Create a link.

<b>Parameters:</b>
name: The name of the link.
url: Specifies where to go when the link is clicked.
*/
function url ( $url, $name ) {
	return "<a href=\"" . BASEURL . "$url\">$name</a>";
}

?>