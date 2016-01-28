# Creating a new repository #

Si vous désirez créer un repository personnel alors voici quelques infos.<br>
Votre site doit générer un flux XML. La structure à respecter est la suivante:<br>
<br>
<br>
<br>
<extensions><br>
<br>
<br>
<blockquote>

<extension>

<br>
<blockquote>

<name>

<br>
<br>
</name><br>
<br>
<br>
<br>
<br>
<version><br>
<br>
<br>
<br>
</version><br>
<br>
<br>
<br>
<br>
<url><br>
<br>
<br>
<br>
</url><br>
<br>
<br>
<br>
<br>
<folder><br>
<br>
<br>
<br>
</folder><br>
<br>
<br>
</blockquote>

</extension>

<br>
<br>
<br>
</extensions><br>
<br>
</blockquote>

Vous pouvez utiliser le SQL suivant pour créer votre base.<br>
<br>
<pre><code>CREATE TABLE IF NOT EXISTS `zef_modules` (<br>
  `id` int(11) NOT NULL auto_increment,<br>
  `name` varchar(45) NOT NULL,<br>
  `version` varchar(10) NOT NULL,<br>
  `description` varchar(255) NOT NULL,<br>
  `url` varchar(255) NOT NULL,<br>
  `folder` varchar(45) NOT NULL,<br>
  PRIMARY KEY  (`id`)<br>
)<br>
</code></pre>

Et le script PHP suivant pour générer votre flux XML:<br>
<br>
<pre><code>&lt;?php<br>
<br>
	mysql_connect ( "localhost", "yourlogin", "yourpassword" );<br>
	mysql_select_db ( "yourdatabase" );<br>
	<br>
	if ( isset ( $_GET['updates'] ) ) {<br>
		unset ( $_GET['updates'] ); <br>
		$results = array(); <br>
		foreach ( $_GET as $helper =&gt; $version ) {<br>
			$sql = 'SELECT * FROM `zef_modules` WHERE `version` != "%s" AND `name` LIKE "%s"';<br>
			$sql = sprintf ( $sql, mysql_real_escape_string ( $version ), mysql_real_escape_string ( $helper ) );<br>
			$result = @mysql_fetch_assoc ( mysql_query ( $sql ) );<br>
			if ( $result ) $results[] = $result;<br>
		}<br>
	} elseif ( isset ( $_GET['search'] ) ) {<br>
		$search = preg_replace ( "/\s+/", "|", trim ( mysql_real_escape_string ( $_GET['search'] ) ) );<br>
		$sql = "SELECT * FROM `zef_modules` WHERE `name` REGEXP \"$search\" OR `description` REGEXP \"$search\"";<br>
		$sql = mysql_query ( $sql );<br>
		while ( $result = @mysql_fetch_assoc ( $sql ) ) $results[] = $result;<br>
	} else {<br>
		$results = array();<br>
		$sql = mysql_query ( "SELECT * FROM `zef_modules`" );<br>
		while ( $result = @mysql_fetch_assoc ( $sql ) ) $results[] = $result;<br>
	}<br>
	<br>
	$xml =  "&lt;?xml version=\"1.0\" encoding=\"utf-8\" ?&gt;&lt;extensions&gt;";<br>
	<br>
	if ( $results ) {<br>
		foreach ( $results as $result ) {<br>
		<br>
			$xml .= "&lt;extension&gt;<br>
	&lt;name&gt;&lt;![CDATA[" . $result['name'] . "]]&gt;&lt;/name&gt;<br>
	&lt;version&gt;&lt;![CDATA[" . $result['version'] . "]]&gt;&lt;/version&gt;<br>
	&lt;description&gt;&lt;![CDATA[" . $result['description'] . "]]&gt;&lt;/description&gt;<br>
	&lt;url&gt;&lt;![CDATA[" . $result['url'] . "]]&gt;&lt;/url&gt;<br>
	&lt;folder&gt;&lt;![CDATA[" . $result['folder'] . "]]&gt;&lt;/folder&gt;<br>
	&lt;/extension&gt;";<br>
		<br>
		}<br>
	}<br>
	<br>
	$xml .= "&lt;/extensions&gt;";<br>
	<br>
	header("Content-Type: text/xml; charset=utf-8");<br>
	echo $xml;<br>
<br>
?&gt;<br>
</code></pre>

Pour terminer, ajoutez l'url de votre script dans le manager.<br>
<br><br>