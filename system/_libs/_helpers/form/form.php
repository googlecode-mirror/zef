<?php
/*
Name: Form
Author: Pierre Cornier
Description: Form helpers.
Version: 1.0
Documentation: auto
*/




/*
<b>Description:</b>
Add the form open tag.

<b>Parameters:</b>
action: Specifies where to send the form-data when the form is submitted.
method: (GET or POST) Specifies how to send data.
name: Specifies the name and the ID of the form.
multipart: (TRUE or FALSE) Specifies if the form is multipart/form-data. 
*/
function form_open ( $action, $method = "POST", $name = "", $multipart = false ) {
	$name = $name != "" ? "name=\"$name\" id=\"$name\"" : "";
	$enctype = $multipart ? "enctype=\"multipart/form-data\"" : "";
	return "<form action=\"" . BASEURL . "$action\" method=\"$method\" $name $enctype>\n";
}



/*
<b>Description:</b>
Add the form closed tag.
*/
function form_close () {
	return "</form>\n";
}






/*
<b>Description:</b>
Create a DropDown menu.

<b>Parameters:</b>
name: Specifies the name and the ID.
values: (numeric or associative array) Specifies the keys and values.
selected: The selected value.
usekey: (TRUE or FALSE) Use the keys of the associative array.
*/
function form_dropdown ( $name, $values, $selected = "", $usekey = true ) {

	$html = "<select name=\"$name\" id=\"$name\">\n";
	if ( $usekey ) {
		foreach ( $values as $k => $v ) {
			$opt = ( $k == $selected ) ? "selected='selected'" : "";
			$html .= "<option value=\"$k\" $opt>$v</option>\n";
		}
	} else {
		foreach ( $values as $v ) {
			$opt = ( $v == $selected ) ? "selected='selected'" : "";
			$html .= "<option value=\"$v\" $opt>$v</option>\n";
		}
	}
	$html .= "</select>\n";
	return $html;
	
}





/*
<b>Description:</b>
Create a CheckBox button.

<b>Parameters:</b>
name: Specifies the name and the ID.
value: Specifies the initial value.
*/
function form_checkbox ( $name, $value = "" ) {
	return "<input name=\"$name\" type=\"checkbox\" value=\"$value\" />\n";
}





/*
<b>Description:</b>
Create a button.

<b>Parameters:</b>
name: Specifies the name and the ID.
location: Specifies the destination URL.
*/
function form_button ( $value, $location ) {
	return "<input type=\"button\" value=\"$value\" onclick=\"document.location='" . BASEURL . "$location'\" />\n";
}






/*
<b>Description:</b>
Create a text input.

<b>Parameters:</b>
name: Specifies the name and the ID.
value: Specifies the initial value.
style: Specifies the CSS style attribute.
js: (associative array) Specifies the javascript events. Ex: array ( 'onclick' => 'alert()' )
*/
function form_input ( $name, $value = "", $style = "", $js = array() ) {
	$events = "";
	foreach ( $js as $k => $v ) $events .= "$k=\"$v\"";  
	return "<input type=\"text\" value=\"$value\" name=\"$name\" id=\"$name\" style=\"$style\" $events />\n";
}





/*
<b>Description:</b>
Create a TextArea.

<b>Parameters:</b>
name: Specifies the name and the ID.
value: Specifies the initial value.
*/
function form_textarea ( $name, $value = "" ) {
	return "<textarea name=\"$name\" id=\"$name\">$value</textarea>\n";
}





/*
<b>Description:</b>
Create a Submit button.

<b>Parameters:</b>
value: Specifies the initial value.
*/
function form_submit ( $value ) {
	return "<input type=\"submit\" value=\"$value\" />\n";
}





/*
<b>Description:</b>
Create a hidden input.

<b>Parameters:</b>
name: Specifies the name and the ID.
value: Specifies the initial value.
*/
function form_hidden ( $name, $value = "" ) {
	return "<input type=\"hidden\" value=\"$value\" name=\"$name\" id=\"$name\" />\n";
}





/*
<b>Description:</b>
Create a file input.

<b>Parameters:</b>
name: Specifies the name and the ID.
*/
function form_file ( $name ) {
	return "<input type=\"file\" name=\"$name\" id=\"$name\" />\n";
}






/*
<b>Description:</b>
Create an auto generated form

<b>Parameters:</b>
model: Reference to a model object
tablename: The table name
where: The 'where' clause
*/
function form_autofields ( $model, $tablename, $where = "" ) {
	// todo: sqlite compatibility
	if ( $where != "" ) {
		$model->db->query ( "SELECT * FROM `$tablename` WHERE $where" );
		$record = $model->db->fetch();
	}
	$form = "";
	$model->db->query ( "DESCRIBE $tablename" );
	$result = $model->db->fetchAll();
	
	foreach ( $result as $col  ) {
		$name = ucwords ( str_replace ( "_", " ", $col['Field'] ) );
		$value = isset ( $record->{$col['Field']} ) ? $record->{$col['Field']} : "";
		if ( $col['Key'] == "PRI" ) {
			$form .= form_hidden ( $col['Field'], $value );
		} else {
			if ( preg_match ( "/text/i", $col['Type'] ) ) {
				$form .= "<label>$name</label>\n";
				$form .= form_textarea ( $col['Field'], $value );
			} else {
				$form .= "<label>$name</label>\n";
				$form .= form_input ( $col['Field'], $value );
			}
		}
	}
	return $form;
}
?>