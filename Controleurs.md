# Les contrôleurs #

Les contrôleurs doivent être placés dans le dossier /system/controllers/.<br>
Si vous n'avez pas modifié la constante <b>DEFAULT_CONTROLLER</b> alors commencez par créer un fichier nommé 'main.php' dans ce répertoire et copiez/collez le code suivant:<br>
<br>
<pre><code>&lt;?php<br>
<br>
class main extends controller {<br>
<br>
    // constructeur<br>
    public function main()<br>
    {<br>
        parent::controller();<br>
        // placez ici vos actions d'initialisation<br>
    }<br>
<br>
    // action par défaut<br>
    public function index()<br>
    {<br>
        // placez ici vos actions<br>
    }<br>
<br>
}<br>
<br>
?&gt; <br>
</code></pre>

<br><br>

<h3>Pour charger un modèle</h3>

Lors de l'initialisation ou ailleurs, vous pouvez utiliser le loader pour charger des modèles, des helpers ou des vues. Pour charger un modèle, utiliser l'action suivante:<br>
<br>
<pre><code>$this-&gt;load-&gt;model ( "mymodel" )<br>
</code></pre>

Le modèle /system/models/mymodel.php sera alors chargé. Vous pourrez ensuite faire appel à ses méthodes et fonctions de la manière suivante:<br>
<br>
<pre><code>$this-&gt;mymodel-&gt;myfunction()<br>
</code></pre>

<br><br><br>

<h3>Pour charger un helper</h3>

Les helpers sont des ensembles de fonctions globales. Vous pouvez les charger de la manière suivante:<br>
<br>
<pre><code>$this-&gt;load-&gt;helper ( "form" );<br>
</code></pre>

Utilisez le manager pour obtenir la liste des helpers installés ou disponibles.<br>
<br>
<br><br><br>

<h3>Pour charger une vue</h3>

Les vues sont chargées de la manière suivante:<br>
<br>
<pre><code>$this-&gt;load-&gt;view ( "myview" );<br>
</code></pre>

La vue /system/views/myview.php sera alors chargée et affichée.<br>
Vous pouvez passer des paramètres à la vue:<br>
<br>
<pre><code>$data['title'] = "My Hello Page";<br>
$data['message'] = "Hello world !";<br>
$this-&gt;load-&gt;view ( "myview", $data );<br>
</code></pre>

Les variables $title, $message de l'exemple ci-dessus seront alors directement accessibles dans la vue. Voici le code la page myview.php:<br>
<br>
<pre><code>&lt;html&gt;<br>
&lt;head&gt;<br>
&lt;title&gt;&lt;?=$title?&gt;&lt;/title&gt;<br>
&lt;/head&gt;<br>
&lt;body&gt;<br>
&lt;?=$message?&gt;<br>
&lt;/body&gt;<br>
&lt;/html&gt;<br>
</code></pre>

Vous pouvez créer des pages composées de plusieurs vues. Par exemple:<br>
<br>
<pre><code>$data['header'] = $this-&gt;load-&gt;view ( "header", array(), true );<br>
$data['body'] = $this-&gt;load-&gt;view ( "body", array(), true );<br>
$data['footer'] = $this-&gt;load-&gt;view ( "footer", array(), true );<br>
$this-&gt;load-&gt;view ( "myview", $data );<br>
</code></pre>

La code de la page myview.php:<br>
<br>
<pre><code>&lt;?=$header?&gt;<br>
&lt;?=$body?&gt;<br>
&lt;?=$footer?&gt;<br>
</code></pre>

<br><br><br>