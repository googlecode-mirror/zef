<?php
/*
Name: Calendar
Author: Pierre Cornier
Description: JS Datepicker is based on the work of Julian Robichaux.
Version: 1.0
Documentation: auto
*/


function calendar_required () {
	echo "<script type=\"text/javascript\" src=\"" . BASEURL . "system/_libs/_helpers/calendar/calendar.js\"></script>\n";
	echo "<link href=\"" . BASEURL . "system/_libs/_helpers/calendar/calendar.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
}

function calendar_set ( $fieldname, $format = "dmy", $separator = "/" ) {
	echo "<script type=\"text/javascript\">\n";
	echo "document.getElementById('$fieldname').onclick = function() {\n";
	echo "displayDatePicker('$fieldname', false, '$format', '$separator');\n";
	echo "}\n";
	echo "</script>\n";
}

?>