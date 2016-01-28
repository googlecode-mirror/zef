# Installation et configuration #

Décompressez simplement l'archive à la racine de votre site puis modifiez le fichier system/config.php.



### config.php ###

| **BASEFOLDER** | Si vous installez Zef dans un sous-dossier alors vous devez spécifier le nom de celui-ci grâce à la constante **BASEFOLDER**.|
|:---------------|:-----------------------------------------------------------------------------------------------------------------------------|
| **ADMIN\_PASSWD** | Le mot de passe par défaut pour accéder au manager (/system/index.php).                                                      |
| **DEFAULT\_CONTROLLER** | Le contrôleur par défaut qui est appelé si il n'y en a aucun de spécifié dans l'url. Zef décompose l'adresse de la manière suivante: dossier\_racine/contrôleur/action. Vous pouvez éditer le fichier system/system.php pour comprendre comment sont appelés les actions et les contrôleurs.|
| **DEFAULT\_ACTION** | L'action par défaut si pas d'action spécifiée.                                                                               |
| **MODEL\_AUTOCONNECT** | Si la constante **MODEL\_AUTOCONNECT** est réglée sur TRUE, alors les modèles sont automatiquement connectés à la base de données. |
| **DB\_TYPE**   | Vous permet de choisir le pilote de la base de donnée (voir [Pilotes PDO](http://fr.php.net/manual/fr/pdo.drivers.php)). Si vous utilisez sqlite comme driver, alors la base de donnée sera placée dans le dossier /system/_libs/_database/. Pensez à protéger ce dossier avec un .htaccess !|
| **DB\_HOST**   | Le serveur de la base de donnée, généralement "localhost".                                                                   |
| **DB\_LOGIN**  | Login de la base.                                                                                                            |
| **DB\_PASSWORD** | Mot de passe de la base.                                                                                                     |
| **DB\_NAME**   | Nom de la base.                                                                                                              |

<br>
<a href='Controleurs.md'>Vous pouvez maintenant lire la petite intro sur les contrôleurs.</a>
<br><br>