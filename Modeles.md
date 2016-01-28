# Les modèles #

Utilisez les modèles pour faire vos requêtes dans vos bases. Voici l'exemple du code d'un modèle:

```
<?php

class mymodel extends model {

    function readnews()
    {
        $this->db->query ( "SELECT * FROM `news`" );
        return $this->fetchAll();
    }

}

?>
```

Utilisez ensuite les contrôleurs pour envoyer les informations à vos vues.
Dans le source de votre contrôleur:

```
$this->load->model ( "mymodel" );
$data['news'] = $this->mymodel->readnews();
$this->load->view ( "news", $data );
```

Puis dans votre vue:

```
...
<body>

<?php foreach ( $news as $record ) : ?>

    <h2><?=$record->title?></h2>
    <p><?=$record->content?></p>
    <a href="<?=$record->url?>" >Read more...</a>

<?php endforeach; ?>

</body>
</html>
```

<br><br>

<h3>Les handlers</h3>

Vous pouvez déclarer des handlers pour filtrer les résultats des requêtes avant des les afficher. Si par exemple vous stockez dans votre base des timestamps, il serait préférable de les convertir avant des les afficher. Vous pouvez faire ça de la manière suivante:<br>
<br>
<pre><code>&lt;?php<br>
<br>
class mymodel extends model {<br>
<br>
    function readnews()<br>
    {<br>
        $this-&gt;db-&gt;addHandler ( "date", "timestampToDate" );<br>
        $this-&gt;db-&gt;query ( "SELECT * FROM `news`" );<br>
        return $this-&gt;fetchAll();<br>
    }<br>
<br>
    function timestampToDate ( $data )<br>
    {<br>
        return date ( "Y/m/d", $data );<br>
    }<br>
<br>
}<br>
<br>
?&gt;<br>
</code></pre>

Attention, pensez à nettoyer vos handlers grâce à la fonction freeHandlers(): $this->db->freeHandlers(). Dans l'exemple précédent, il serait préférable d'utiliser:<br>
<br>
<pre><code>    function readnews()<br>
    {<br>
        $this-&gt;db-&gt;addHandler ( "date", "timestampToDate" );<br>
        $result = $this-&gt;db-&gt;query ( "SELECT * FROM `news`" );<br>
        $this-&gt;db-&gt;freeHandlers();<br>
        return $result;<br>
    }<br>
</code></pre>


<br><br>