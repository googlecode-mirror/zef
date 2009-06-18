<?php
	
	require "config.php";
	require HELPERSPATH . "jquery/jquery.php";
	
	@session_start();
	
	if ( isset ( $_POST['passwd'] ) ) $_SESSION['passwd'] = $_POST['passwd'];
	
	$login = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

<div id="page">

	<div id="header">
		<div id="title">Extension Manager</div>
	</div>

	<div id="navigation">
	</div>

	<div id="login">
	<form method="post" action="index.php">
	  Password:
	  <input type="password" id="password" name="passwd" />
	  <input type="submit" id="submit" value="Login" />
	</form>
	</div>
	
</div>
	
</body>
</html>';
	
	if ( !isset ( $_SESSION['passwd'] ) || $_SESSION['passwd'] != ADMIN_PASSWD ) {
		echo $login;
		exit();
	}
	
	if ( isset ( $_GET['logout'] ) ) {
		unset ( $_SESSION['passwd'] );
		echo $login;
		exit();
	}
	
	if ( isset ( $_GET['doc'] ) && !empty ( $_GET['doc'] ) ) {
		
		$src = file_get_contents ( HELPERSPATH . "$_GET[doc]/$_GET[doc].php" );
		
		preg_match ( "#/\*(.+?)\*/#s", $src, $comments );
		preg_match_all ( "/(.+?):(.+?)\n/", $comments[1], $helper_info );
		$helper_info = array_change_key_case ( array_combine ( $helper_info[1], $helper_info[2] ) );
		
		$src = preg_replace ( "#/\*.+?\*/#is", "", $src, 1 );
		preg_match_all ( "#(/\*(.+?)\*/)*[^*]*?function\s+([^\n{]+)#is", $src, $functions_info );
		
		$functions_html = "<ul>\n";
		for ( $i = 0; $i < count ( $functions_info[2] ); $i++ ) {
			$functions_html .= "<li>\n";
			$functions_html .= "<h4 onclick=\"$('#function_$i').toggle();$('#arrow_$i').html($('#function_$i').is(':visible')?'&#9660':'&#9658');\" title='click to show/hide'><span id='arrow_$i' style='font-size: 10px'>&#9658</span> " . $functions_info[3][$i] . "</h4>\n";
			$functions_html .= "<p id='function_$i' style='display: none'>\n";
			$functions_html .= $functions_info[2][$i] != "" ? nl2br ( trim ( $functions_info[2][$i] ) ) : "No documentation";
			$functions_html .= "</p>\n";
			$functions_html .= "</li>\n";
		}
		$functions_html .= "</ul>\n";
		
		$page = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>' . ucfirst ( $_GET['doc'] ) . ' Helper Documentation</title>
<link rel="stylesheet" type="text/css" href="styles.css">
' . jquery_required ( false ) . '
</head>

<body>
<div id="page">
	<div id="header">
		<div id="title">' . $helper_info['name'] . 'v' . $helper_info['version'] . ' Documentation</div>
		<div id="logout"><a href="?logout">Logout</a></div>
	</div>
	
	<div id="navigation">
		<a href="javascript:history.back();"><< Back to the manager</a>
	</div>
	
	<div id="help">
	
		<h3>Functions</h3>
		<input type="submit" value="expand" id="expand" onclick="$(\'#help p\').show();$(\'#help h4 span\').html(\'&#9660\');" style="cursor: pointer">
		<input type="button" id="collapse" value="collapse" onclick="$(\'#help p\').hide();$(\'#help h4 span\').html(\'&#9658\');" style="cursor: pointer">
		' . $functions_html . '
	
	</div>
	
	<div style="clear: both"></div>
	<div id="footer">This documentation is auto generated.</div>
</div>
</body>
</html>';

		echo $page;
		exit();
	}
	
	
	// add source
	if ( isset ( $_POST['sourceurl'] ) ) {
		$sources = parse_ini_file ( "sources.ini" );
		$handle = fopen ( "sources.ini", "w" );
		foreach ( $sources as $name => $url ) fwrite ( $handle, $name . "=\"" . $url . "\"\n" );
		fwrite ( $handle, $_POST['sourcename'] . "=\"" . $_POST['sourceurl'] . "\"\n" );
		fclose ( $handle );
		exit();
	}
	
	// get source list
	if ( isset ( $_GET['getsourceslist'] ) ) {
		$sources = parse_ini_file ( "sources.ini" );
		$html = "";
		foreach ( $sources as $name => $url ) {
			$html .= "<div id=\"source\" onmouseover=\"$(this).css({'background-color' : '#000', 'color' : '#fff'})\" onmouseout=\"$(this).css({'background' : 'none', 'color': '#000'})\" style=\"cursor: pointer\" onclick=\"if(confirm('Remove $name ?'))$.post('index.php',{deletesource:'$name'}, function(){reloadlist()});\"> &#8226; $name : $url</div>";
		}
		echo $html;
		exit();
	}
	
	// delete source
	if ( isset ( $_POST['deletesource'] ) ) {
		$sources = parse_ini_file ( "sources.ini" );
		unset ( $sources[$_POST['deletesource']]  );
		$handle = fopen ( "sources.ini", "w" );
		foreach ( $sources as $name => $url ) fwrite ( $handle, $name . "=\"" . $url . "\"\n" );
		fclose ( $handle );
	}
	
	// load updates
	if ( isset ( $_GET['listupdates'] ) ) {
		$handle = opendir ( HELPERSPATH );
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( file_exists ( HELPERSPATH . "$file/$file.php" ) ) {
				$src = file_get_contents ( HELPERSPATH . "$file/$file.php" );
				preg_match ( "#/\*(.+?)\*/#s", $src, $comments );
				preg_match_all ( "/(.+?):(.+?)\n/", $comments[1], $helper_info );
				$result_array = array_change_key_case ( array_combine ( $helper_info[1], $helper_info[2] ) );
				$result_array['folder'] = $file;
				$infos[] = $result_array;
			}
		}
		closedir ( $handle );
		$data = "";
		foreach ( $infos as $info ) $data .= trim ( $info['folder'] ) . "=" . trim ( $info['version'] ) . "&";
		$sources = parse_ini_file ( "sources.ini" );
		$html = "";
		foreach ( $sources as $name => $url ) {
			$result = @file_get_contents ( "$url?{$data}&updates" );
			if ( $result ) {
				$html .= "<div id='repo_title'>Available updates for " . ucfirst ( $name ) . "</div>";
				$XML = simplexml_load_string ( $result );
				foreach ( $XML as $node ) {
					$html .= "<div id='extension'>\n";
					$html .= "<div id='title'>{$node->name} version {$node->version}</div>\n";
					$html .= "<div id='description'>{$node->description}</div>\n";
					$html .= "<div id='install'><a href='#' onclick=\"if(confirm('Install this update ?'))document.location='index.php?install={$node->url}';\">Update</a></div>\n";
					$html .= "</div>\n";
				}
				if ( !$XML->children() ) $html .= "no update available.";
			}
		}
		if ( $html == "" )
			echo "no update available.";
		else
			echo $html;
		exit();
	}
	
	// search
	if ( isset ( $_POST['search'] ) ) {
		$sources = parse_ini_file ( "sources.ini" );
		$html = "";
		foreach ( $sources as $name => $url ) {
			$result = @file_get_contents ( "$url?search=$_POST[search]" );
			if ( $result ) {
				$html .= "<div id='repo_title'>Search results [" . ucfirst ( $name ) . "]</div>";
				$XML = simplexml_load_string ( $result );
				foreach ( $XML as $node ) {
					$html .= "<div id='extension'>\n";
					$html .= "<div id='title'>{$node->name} version {$node->version}</div>\n";
					$html .= "<div id='description'>{$node->description}</div>\n";
					$html .= "<div id='install'><a href='#' onclick=\"if(confirm('Install this extension ?'))$.post('index.php',{install : '{$node->url}', folder : '{$node->folder}'}, function(e){if(e)alert(e);});\">Install</a></div>\n";
					$html .= "</div>\n";
				}
			}
		}
		if ( $html == "" )
			echo "no results.";
		else
			echo $html;
		exit();
	}
	
	// get helpers list
	if ( isset ( $_GET['gethelperslist'] ) ) {
		$infos = array();
			
		$handle = opendir ( HELPERSPATH );
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( file_exists ( HELPERSPATH . "$file/$file.php" ) ) {
				$src = file_get_contents ( HELPERSPATH . "$file/$file.php" );
				preg_match ( "#/\*(.+?)\*/#s", $src, $comments );
				preg_match_all ( "/(.+?):(.+?)\n/", $comments[1], $helper_info );
				$result_array = array_change_key_case ( array_combine ( $helper_info[1], $helper_info[2] ) );
				$result_array['folder'] = $file;
				$infos[] = $result_array;
			}	
		}
		closedir ( $handle );
		sort ( $infos );
		
		$html = '';
		foreach ( $infos as $info ) {
			$html .= '
			<div id="helper">
				<div id="title">' . $info['name'] . ' version' . $info['version'] . '</div>
				<div id="description">
					' . $info['description'] . '<br />
					Author: ' . $info['author'] . '<br />
					<a href="' . ( preg_match("/http/i",$info['documentation'])?$info['documentation']:'?doc=' . $info['folder'] ) . '">Documentation</a><br />
					<a href="#" onclick="javascript:if(confirm(\'Are you sure ?\'))$.post(\'index.php\', {\'uninstall\' : \'' . $info['folder'] . '\'}, function(){reloadhelperslist();});">Uninstall</a>
				</div>
			</div>';
			 
		}
		echo $html;
		exit();
	}
	
	
	// uninstall
	if ( isset ( $_POST['uninstall'] ) ) {
		delete ( HELPERSPATH . $_POST['uninstall'] );
		exit();
	}
	
	function delete ( $folder ) {
		$handle = opendir ( $folder );
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( $file != "." && $file != ".." ) {
				if ( is_dir ( "$folder/$file" ) ) {
					delete ( "$folder/$file" );
				 } else {
				 	unlink ( "$folder/$file" );
				}
			}
		}
		closedir ( $handle );
		rmdir ( $folder );
	}
	
	
	// install
	if ( isset ( $_POST['install'] ) ) {
		
		if ( file_exists ( HELPERSPATH . $_POST['folder'] ) ) die ( "This helper has already been successfully installed !" );
		
		if ( !function_exists ( "zip_open" ) ) die ( "ZipArchive is required to install this helper.\nBut you can try to install it manually." );
	
		$fsrc = fopen ( $_POST['install'], "r" );
		$fdest = fopen ( HELPERSPATH . "helper.zip", 'w+' );
		stream_copy_to_stream ( $fsrc, $fdest );
		fclose ( $fsrc );
		fclose ( $fdest );
		
		unzip ( HELPERSPATH . "helper.zip", HELPERSPATH );
		unlink ( HELPERSPATH . "helper.zip" );
		
		echo "Helper has been successfully installed !";
		
		exit();
		
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Extension Manager</title>
<link rel="stylesheet" type="text/css" href="styles.css">
<?=jquery_required()?>
<script type="text/javascript">
function reloadupdates() {
	$.get('index.php?listupdates', 
		function(e) {
			$('#available').html(e);
		}
	);
}
function reloadlist(){
	$.get ( 'index.php?getsourceslist',
		function(e){
			$('#sourceslist').html(e);
			$('#sourcename').val('');
			$('#sourceurl').val('http://');
		}
	);
}
function reloadhelperslist(){
	$.get ( 'index.php?gethelperslist',
		function(e){
			$('#helpers').html(e);
		}
	);
}
reloadlist();
reloadhelperslist();
</script>
</head>

<body>

<div id="page">

	<div id="header">
		<div id="title">Extension Manager</div>
		<div id="logout"><a href='?logout'>Logout</a></div>
	</div>
	
	<div id="navigation">
		<ul>
			<li onclick="$('#helpers').html('Please wait...');$('#body div').hide();$('#helpers_list, #helpers_list *').show();reloadhelperslist();">Manage</li>
			<li onclick="$('#available').html('Please wait...');$('#body div').hide();$('#updates, #updates *').show();reloadupdates();">Updates</li>
			<li onclick="$('#body div').hide();$('#sources, #sources *').show();">Sources</li>
			<li onclick="$('#body div').hide();$('#search, #search *').show();">Search</li>
		</ul>
	</div>
	
	<div id="body">
	
		<div id="helpers_list">
		
			<h3>Installed extensions</h3>
		
			<div id='helpers'>Please wait...</div>
		
		</div>
	
	  <div id="updates" style="display: none">
	  		<h3>Updates</h3>

			<div id="available">Please wait...</div>
	  </div>
	
	
	  <div id="sources" style="display: none">
		
			<h3>Sources Repository</h3>
			
			<label>Name:</label><input type="text" name="sourcename" id="sourcename"/><br />
			<label>URL:</label><input name="sourceurl" id="sourceurl" type="text" value="http://" size="50" />
		    <input type="button" value="Add source" id="submit" onclick="$.post('index.php', {sourceurl:  $('#sourceurl').val(), sourcename: $('#sourcename').val()}, function(){reloadlist();})"/>
			
			<div style="background-color: #000; height: 1px; margin: 10px 0px 10px 0px"></div>
			

			<div id="sourceslist"></div>
			
	  </div>
	
		<div id="search" style="display: none">
		
			<h3>Search</h3>
			<label style="width: 50px">Search:</label><input name="terms" type="text" id="terms" size="40"/>
		    <input type="button" value="Search" id="submit" onclick="$('#searchresults').html('Please wait...');$.post('index.php', {search:  $('#terms').val()}, function(e){$('#searchresults').html(e);})"/>
			
			<div style="background-color: #000; height: 1px; margin: 10px 0px 10px 0px"></div>
			
			<div id="searchresults"></div>
			
		</div>
	
	
	</div>
	
	<div style="clear: both"></div>
	
	<div id="footer">Warning, the extension manager needs <b>jQuery helper</b>, <b>ZipArchive</b> and <b>SimpleXML</b> to run properly.</div>

</div>

</body>
</html>

<?php

function unzip($file, $path='') {
	$zip = zip_open($file);
	if ( $zip ) {
		while ( $zip_entry = zip_read ( $zip ) ) {
			if ( zip_entry_filesize ( $zip_entry ) > 0 ) {
				$full_path = $path . dirname ( zip_entry_name ( $zip_entry ) );
				$filename = zip_entry_name($zip_entry);
				$full_name = $path . $filename; 
				if ( !file_exists ( $full_path ) ) {
					$tmp = '';
					foreach ( explode ( '/', $full_path ) as $k ) {
						$tmp .= $k.'/';
						if ( !file_exists ( $tmp ) ) mkdir($tmp, 0755);
					}
				}
				if ( zip_entry_open ( $zip, $zip_entry, "r" ) ) {
					$fd = fopen ( $full_name, 'w' );
					fwrite ( $fd, zip_entry_read ( $zip_entry, zip_entry_filesize ( $zip_entry ) ) );
					fclose ( $fd );
					zip_entry_close ( $zip_entry );
				}
			}
		}
		zip_close ( $zip );
	}
}

?>