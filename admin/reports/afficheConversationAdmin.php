<?php
//on démarre une session
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 3)) { ?>
  <!DOCTYPE html>
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Outils d'administration</title>
    <link rel="stylesheet" type="text/css" href="/messagerie/messagerieGenerale.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
    <script type="text/javascript" src="./messagerieAdmin.js"></script>
  </head>

  <?php

  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


  function phpAlert($msg)
  {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
  }

   // Merci stack Overflow - https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
   function startsWith($haystack, $needle)
   {
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
   }

  ?>
  <body>
    <!--Début bloc de présentation-->
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Outils d'administration</h1>
    </div>
    <div class="menu">
      <ul>
        <li><a href="/home/accueil.php">Accueil</a></li>
        <?php if (intval($_SESSION['login_Type']) >= 2) { ?>
        <li><a class="active" href="">Aperçu conversation</a></li>
        <li><a href="/admin/membres.php">Liste des Utilisateurs</a></li>
        <li><a href="/admin/reports/listeReports.php">Liste des Signalements</a></li>
        <li><a href="/admin/conversations.php">Liste des Conversations</a></li>
        <?php } ?>
      </ul>
    </div>
    <!--Fin bloc de présentation-->
        <div id="textConv">Conversation :</div>

        <div id="blocConv">
          <?php
           if (($_SERVER["REQUEST_METHOD"] == "GET") && (isset($_GET["convID"]))) { 
            $leChemin = "./../../messagerie/" . $_GET["convID"]; // on crée le chemin vers le fichier
            $tmpFileName = explode("_",$_GET["convID"]); // on récupère le pseudo 1 de la conversation
          if (file_exists($leChemin)) {
            //on récupère le contenu du fichier à savoir la conversation
            $conversation = file_get_contents($leChemin);
            $nbrMsg = explode("\n", $conversation);
            $j = 0;
            while (($j < count($nbrMsg) - 1)) {
              /*on met ce qui est entre les § dans des cases d'un tableau afin de pouvoir
        récupérer les différentes données présentes dans chaque ligne*/
              $message = explode('§', $nbrMsg[$j]);
              if (startsWith($message[1],$tmpFileName[0] . "_")) { // on utilise le pseudo1 du nom de la conversation afin de mettre un utilisateur à droite et l'autre à gauche en CSS
                echo "<div class ='user1' onclick='deleteMsg(" . '"' . $message[1] . '"' .",". '"' . $_GET["convID"] . '"' . ")'>" . $message[0] . "</div> <br> "; // chaque message peut être supprimé en cliquant dessus
              } else {
                echo "<div class ='user2' onclick='deleteMsg(" . '"' . $message[1] . '"' .",". '"' . $_GET["convID"] . '"' . ")'>" . $message[0] . "</div> <br> ";
              }
              $j++;
            }
          } else {
            echo ("Le fichier n'existe pas.");
          }
          ?>
              </div>
              <!--fin partie boutons-->
            </div>

          <?php } else { ?>
            <span> Vous n'avez pas renseigné de fichier.</span>
          <?php } ?>
        </div>
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
    //unset($_SESSION['user']);
  } else {
    header("Location: /home/accueil.php");
  }
?>
