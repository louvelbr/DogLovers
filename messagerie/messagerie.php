<?php
//on démarre une session
session_start();
//on vérifie que l'utilisateur est abonné sinon il ne peut pas regarder ses messages
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) >= 2)) { ?>
  <!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1−strict.dtd">
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Le site de rencontre pour les amoureux des chiens.</title>
    <link rel="stylesheet" type="text/css" href="./messagerieGenerale.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
    <script type="text/javascript" src="messagerie.js"></script>
  </head>

  <?php
  /*cette fonction permet de */
  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  /*cette fonction permet de */
  function phpAlert($msg)
  {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
  }

  $erreur = false;
  //on récupère le pseudo de la personne à qui l'on parle si la methode est get
  if (($_SERVER["REQUEST_METHOD"] == "GET") && (isset($_GET["user"]))) {
    //on le stock dans une variable de session
    $_SESSION["user"] = $_GET["user"];
    //et dans une variable $user
    $user = $_SESSION["user"];
    //si la variable n'est pas initialisée
  } else if (!isset($_SESSION["user"])) {
    //on informe que le destinataire n'existe pas
    phpAlert("Aucun utilisateur en base.");
    //sinon on récupère la variable de session contenant le pseudo du destinataire
  } else {
    $user = $_SESSION["user"];
  }

  // Merci stack Overflow - https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
  /*Cette fonction permet de   */
  function startsWith($haystack, $needle)
  {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  ?>
  <body>
    <!--Début bloc de présentation contenant les différents boutons permettants de naviguer entre les pages-->
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Profil de <?php echo ($_SESSION["user"]); ?></h1>
    </div>
    <div class="menu">
      <ul>
        <li><a href="../home/accueil.php">Accueil</a></li>
        <li><a href="./../profil/profil.php?user=<?php echo $_SESSION["user"]; ?>">Infos <?php echo ($_SESSION["user"]); ?></a></li>
        <!--on regarde si la personne est abonnée-->
        <?php if (intval($_SESSION['login_Type']) >= 2) { ?>
          <li><a class="active" href="">Conversation avec <?php echo $_SESSION["user"]; ?></a></li>
        <?php } ?>
        <li class="deconnexion"><a href="./../login/logout.php">Deconnexion</a></li>
        <!--on regarde si la personne est admin-->
        <?php if (intval($_SESSION['login_Type']) === 3) { ?>
          <li><a <?php echo "href='../admin/bannir/bannir.php?user=". $user ."'"?>>Bannir <?php echo ($user); ?></a></li>
          <li><a <?php echo "href='../admin/bannir/debannir.php?user=". $user ."'"?>>Debannir <?php echo ($user); ?></a></li>
          <li><a <?php echo "href='../admin/bannir/supprimerCompte.php?user=". $user ."'"?>>Supprimer <?php echo ($user); ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <!--Fin bloc de présentation-->
    <?php
    //on initialise les variables
    $banned = false;
    $canSend = true;
    $lastvalue = true;
    $path = "./../register/data/userList.txt"; // chemin fichier utilisateur
    $file = fopen($path, 'r'); // ouverture du fichier
    if ($file) { // si le fichier est bien ouvert alors
      $lastvalue = true;
      while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
        $userData = explode("§", $line); // séparation des données de la ligne utilisateur
        //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
        if ($userData[0] == $user) { // si le pseudo match avec une entrée en BDD
          if ($userData[sizeOf($userData) - 6] == "banned") {
            $banned = true;
          }
          // if($userData[sizeOf($userData) - 6] == "free") {
          //   $canSend = false;
          // }
          $lastvalue = false;
        }
      }
      fclose($file);
    } else {
      phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
    }
    $messageValide = true;
    if (isset($_SESSION["user"])) { //&& $canSend) {
      //on recupère les deux pseudos
      $nomFichier = array($_SESSION['pseudo'], $_SESSION['user']);
      //on les tri par ordre alphabétique
      usort($nomFichier, "strnatcmp");
      //on vérifie si la zone de message a bien été remplie
      if (empty($_POST["message"]) || $_POST["message"] == "0") {
        //si ce n'est pas le cas la variable qui regarde si le message est valid prend faux
        $messageValide = false;
        //si le message est rempli
      } else {
        $message = test_input($_POST["message"]);
        //alors on vérifie s'il contient des caractères interdits
        if (!preg_match("/[^§]+/", $message)) {
          //si c'est le cas on le signal
          $erreurMessage = "Le Message contient des caractères interdits.";
          //et la variable prend faux
          $messageValide = false;
        }//fin de vérification des caractères
      }//fin de vérification de message vide ou non
      //on récupère l'heure sous forme heure : minutes
      $heure = date("H:i");
      //on regarde si le message est valide donc s'il n'est pas vide et qu'il ne contient pas de caractères interdits
      if ($messageValide) {
        //on met dans content ce qu'on veut écrire dans le fichier à savoir
        //l'heure d'envoi du message le pseudo de la personne qui à envoyé le message et le message
        $content = $heure . " " . $_SESSION['pseudo'] . " : " . $message . "§" . uniqid($_SESSION['pseudo'] . "_") . "\n";
        //on met le contenu dans le fichier nommé pseudo1_pseudo2.txt avec pseudo1 et 2 triés par ordre alphabétique
        //le fichier est créé s'il n'éxiste pas
        file_put_contents($nomFichier[0] . '_' . $nomFichier[1] . '.txt', $content, FILE_APPEND);
        //on regarde si le fichier destinataires_NomDeLaPersonneConnectée existe et que le message est valide
        if (file_exists('destinataires_' . $_SESSION['pseudo'] . '.txt') && $messageValide) {
          //on récupère le contenu du fichier destinataires_NomDeLaPersonneConnectée.txt
          //ce fichier contient les noms de toutes les personnes à qui la personne connectée a parlé
          $contenu = file_get_contents('destinataires_' . $_SESSION['pseudo'] . '.txt');
          /*on sépare selon les |, cad on stock dans un tableau les noms des différentes personnes
          ainsi par exemple $nomDestinataire[0] sera égale au pseudo de la première personne à qui la personne connectée à parlé.*/
          $nomDestinataire = explode('|', $contenu);
          //on initialise les variables
          //i est la variable qu'on incrémente
          $i = 0;
          /*cette variable permet d'arreter la boucle une fois que l'on a trouvé
          le nom de l'utilisateur que l'on veut bloquer*/
          $destinataireTrouve = false;
          //on récupère le nom de la personne à qui l'on parle donc le destinataire
          $destinataire = $_SESSION['user'];
          //on parcour les noms des destinataires pour voir si le nom du destinataire est déjà présent
          while (($i < sizeof($nomDestinataire) - 1) && !$destinataireTrouve) {
            /*on sépare chaque case du tableau selon les _ car les utilisateurs bloqués sont écrit sous la forme:
            pseudo_bloque ainsi on peut récupérer juste le pseudo de la personne sans _bloqué*/
            $destinataireBis = explode("_",$nomDestinataire[$i]);
            //on regarde si le nom dans le fichier correspond à celui cherché
            if ($destinataireBis[0] == $destinataire) {
              //si c'est le cas on arrête la recherche et donc la boucle
              $destinataireTrouve = true;
            }
            //on passe au nom suivant
            $i++;
          }//fin de la boucle while
          //si on n'a pas trouvé le nom du destinataire dans le fichier
          if (!$destinataireTrouve) {
            //alors on le rajoute
            //on lui donne la bonne forme à savoir pseudo|
            $destinataire = $_SESSION['user'] . '|';
            //puis on le rajoute à la fin du fichier
            file_put_contents('destinataires_' . $_SESSION['pseudo'] . '.txt', $destinataire, FILE_APPEND);
          }
          //si le fichier destinataires_NomDeLaPersonneConnectée n'existe pas
        } else {
          //on donne la bonne forme au pseudo
          $destinataire = $_SESSION['user'] . '|';
          //puis on créé le fichier et on marque le pseudo dedans
          file_put_contents('destinataires_' . $_SESSION['pseudo'] . '.txt', $destinataire, FILE_APPEND);
        }

        /*On fait pareil que précedement mais avec le fichier destinataire de la personne à qui l'on parle afin d'ajouter
        le nom de la personne qui lui parle à son fichier ou de le créer s'il n'existe pas*/
        if (file_exists('destinataires_' . $_SESSION['user'] . '.txt') && $messageValide) {
          $contenu = file_get_contents('destinataires_' . $_SESSION['user'] . '.txt');
          $nomDestinataire = explode('|', $contenu);
          $i = 0;
          $destinataireTrouve = false;
          $destinataire = $_SESSION['pseudo'];
          while (($i < sizeof($nomDestinataire) - 1) && !$destinataireTrouve) {
            if ($nomDestinataire[$i] == $destinataire) {
              $destinataireTrouve = true;
            }
            $i++;
          }
          if (!$destinataireTrouve) {
            $destinataire = $_SESSION['pseudo'] . '|';
            file_put_contents('destinataires_' . $_SESSION['user'] . '.txt', $destinataire, FILE_APPEND);
          }
        } else {
          $destinataire = $_SESSION['pseudo'] . '|';
          file_put_contents('destinataires_' . $_SESSION['user'] . '.txt', $destinataire, FILE_APPEND);
        }//fin de l'ajout dans le fichier destinataire
      }//fin du if($messageValide)
      ?>
      <!--On affiche la conversation-->
      <form accept-charset="UTF-8" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">

        <div id="textConv">Conversation :</div>

        <div id="blocConv">
          <?php
          //on récupère le nom du fichier contenant la discution
          $leChemin = $nomFichier[0] . '_' . $nomFichier[1] . '.txt';
          //si le fichier existe
          if (file_exists($leChemin)) {
            //on récupère le contenu du fichier à savoir la conversation
            $conversation = file_get_contents($leChemin);
            //on sépare selon les lignes pour récupérer les messages séparément
            $nbrMsg = explode("\n", $conversation);
            //on initialise
            $j = 0;
            //on parcourt les messages
            while (($j < count($nbrMsg) - 1)) {
              /*on met ce qui est entre les § dans des cases d'un tableau afin de pouvoir
              récupérer les différentes données présentes dans chaque ligne*/
              $message = explode('§', $nbrMsg[$j]);
              if (startsWith($message[1],$_SESSION["pseudo"] . "_")) {
                //on permet de supprimer le message
                echo "<div class ='user1' onclick='deleteMsg(" . '"' . $message[1] . '"' .",". '"' . $leChemin . '"' . ")'>" . $message[0] . "</div> <br> ";
              } else {
                //ou de le rapporter aux admins
                echo "<div class ='user2' onclick='reportMsg(" . '"' . $message[1] . '"' .",". '"' . $leChemin . '"' . ")'>" . $message[0] . "</div> <br> ";
              }
              //on passe au message suivant
              $j++;
            }
            //si le fichier n'existe pas on propose de démarer la conversation
          } else {
            echo ("<span> Pour démarrer la conversation, envoyez un message ! ☺ </span> <br>");
          }
          ?>
          <?php if (!$lastvalue) {
            //on regarde si l'utilisateur est banni
            if ($banned) { //si c'est la cas on prévient l'utilisateur?>
            <span> Cet utilisateur est banni, il ne recevra vos message qu'à son débanissement.</span> <br>
          <?php }
          //////////bloc qui permet de savoir si l'utilisateur nous a bloqué/////
          //on récupère le contenu du fichier destinataire de celui à qui l'on parle
          $destinataireBloque = false;
          if (file_exists('destinataires_' . $user . '.txt')) {
          $contenu = file_get_contents('destinataires_' . $user . '.txt');
          //on sépare selon | pour récupérer les différents pseudo ou pseudo_bloqué
          $nomDestinataireBloque = explode('|', $contenu);
          //on initalise b à
          $b = 0;
          //on initialise les variables $destinataireBloque qui permet de savoir si on à été bloqué
          //et $destinataireTrouve pour voir si on est bien dans le fichier
          $destinatairetrouve = false;
          //on parcourt les noms des destinataires
          while(($b < sizeof($nomDestinataireBloque) - 1) && !$destinatairetrouve){
            //on sépare selon _ pour récupérer le pseudo et le "bloqué" ou non séparement
            $destinataireBis = explode("_",$nomDestinataireBloque[$b]);
            //on regarde si le noms dans le fichier correspond a celui de la personne connectée
            if($destinataireBis[0] == $_SESSION['pseudo']){
              //si c'est le cas on arrete la recherche
              //$destinataireBis = explode("_",$nomDestinataireBloque[$b]);
              $destinatairetrouve = true;
              //on regarde si l'utilisateur est bloqué
              if(isset($destinataireBis[1]) && $destinataireBis[1]=="bloqué"){
                //si c'est le cas $destinataireBloque prend vrai
                $destinataireBloque = true;
              }//fin de la recher de si l'utilsateur est bloqué
            }//fin de la comparaison des pseudos
            $b++;
          }
          }//fin du while
          ///////////////fin du bloc////////////////
          //si l'utilisateur connecté à été bloqué par son destinataire alors on l'en informe et il ne peut pas écrire de message
          if($destinataireBloque){
            echo("Cet utilisateur vous a bloqué, vous ne pouvez pas lui envoyer de message.");
            //sinon il peut écrire un message
          }else{
            ?>
            <div id="inputEnvoie">
              <input name="message" type="text" pattern="[^§]+" value="" placeholder="Ecrire un message" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /><br>
              <div class="part_boutons">
                <input type="submit" value="Envoyer "></input>
                <?php
              }
              ?>

            </div>
            <!--fin partie boutons-->
          </div>

        <?php } else { ?>
          <span>Cet utilisateur n'existe plus.... il a supprimé son profil</span> <br>
          <span>Vous pouvez choisir de supprimer la conversation via ce boutton : <a href='./supprimerConversation.php?user=<?php echo $user; ?>'><input type='button' id='bouton2' value='Supprimer'> </span>
          <?php } ?>
        </div>

      </form>
    </body>
 <!-- Footer -->
 <footer id="footer">
      <div class="inner">
        <div class="content">
          <section>
            <h3>Dog Lovers</h3>
            <p>Que vous soyez plutôt Bulldog, Caniche ou Labrador, DogLovers est l'entremetteur des dresseurs. DogLovers est un site de rencontre par affinités, dédié aux célibataires qui recherchent une relation durable et épanouie. L'interaction entre nos célibataires se fait dans un environnement sécurisé. Notre équipe est à votre écoute afin de vous offrir la meilleure expérience possible.</p>
            <br>
          </section>
          <section>
            <h4>Liens</h4>
            <ul class="alt">
              <li><a href="/home/accueil.php">Accueil</a></li>
              <li><a href="/profil/MonProfil.php">Mon Profil</a></li>
              <li><a href="/home/conseils.php">Conseils</a></li>

            </ul>
            <br>
          </section>
          <section>
            <h4>Nous contacter</h4>
            <ul class="plain">
              <li><a href="mailto:staff@dog-lovers.fr"><i class="contact">&nbsp;</i>Contact</a></li>
              <li><a href="https://gitlab.etude.eisti.fr/meetandlove/dog-lovers"><i class="github">&nbsp;</i>Github</a></li>
            </ul>
            <br>
          </section>
        </div>
        <div class="copyright">
          <img src="/ressources/favicon.ico"></img>
          <br>
          &copy; DogLovers - Tout droits réservés.
        </div>
      </div>
    </footer>
    </html>
    <?php
  } else{
    echo "<span>Vous ne pouvez pas communiquer avec cet utilisateur, il n'est pas abonné ou alors ce compte n'exite pas.</span>";
  }
  //on libère la variable
  unset($_POST['message']);
  //si l'utilisateur est pas abonné on renvoi une erreur
} else {
  header("Location: /home/accueil.php");
}
?>
