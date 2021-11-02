<?php
//on démarre une session
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) > 0)) { ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Profil</title>
    <link rel="stylesheet" type="text/css" href="./monProfil/MonProfil.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
  </head>

  <body>
    <?php
     function afficher($donneeBis, $i, $j)
     {
       if (!isset($donneeBis[$i][$j]) || ($donneeBis[$i][$j]) == "") {
         $afficher = false;
       } else {
         $afficher = true;
       }
       return ($afficher);
     }
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $user = $_GET["user"]; // récupération ID Utilisateur dont le profil est à afficher
      $_SESSION["user"]=$user;
    ?>
      <div id="blocTitre"></div>
      <div id="Titre">
        <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
        <h1>Profil de <?php echo ($user); ?></h1>
      </div>
      <div class="menu">
        <ul>
          <li><a href="../home/accueil.php">Accueil</a></li>
          <li><a class="active" href="">Infos <?php echo ($user); ?></a></li>
          <?php
          if (intval($_SESSION["login_Type"]) >= 2) { ?>
            <li><a <?php echo 'href="./../messagerie/messagerie.php?user='.$user.'"';?> >Envoyer un message à <?php echo ($user); ?></a></li>
          <?php } ?>
          <li class="deconnexion"><a href="./../login/logout.php">Deconnexion</a></li>
          <?php if(intval($_SESSION['login_Type']) === 3){ ?>
            <li><a <?php echo "href='../admin/bannir/bannir.php?user=". $user ."'"?>>Bannir <?php echo ($user); ?></a></li>
            <li><a <?php echo "href='../admin/bannir/debannir.php?user=". $user ."'"?>>Debannir <?php echo ($user); ?></a></li>
              <li><a <?php echo "href='../admin/bannir/supprimerCompte.php?user=". $user ."'"?>>Supprimer <?php echo ($user); ?></a></li>
            <?php } ?>
        </ul>
      </div>
      <?php
      // Merci stack Overflow - https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
      function startsWith($haystack, $needle)
      {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
      }
      //
      //le profil c'est celui qui est visité  $user
      //l'utilisateur c'est celui qui visite : $_SESSION['pseudo]
      //$utilisateurEnLigne c'est la ligne du fichier de la forme $user§visiteur1|nbvisites§visteur2|nbvisites...
      $contentBis = file_get_contents("../register/data/matchs.txt");
      $utilisateurEnLigne = explode("\n", $contentBis);
      $profilTrouve = false;
      $visiteurTrouve = false;
      $a = 0;
      //on cherche dans le fichier si l'utilisateurenligne y est
      while (($a < count($utilisateurEnLigne) - 1) && !$profilTrouve) {
        //on met les diférents noms de visiteurs ainsi que leur nombre de visites dans des cases
        $detailUtilisateur = explode("§", $utilisateurEnLigne[$a]);
        //trim permet de supprimer les espaces en début et en fin de chaîne -- Louve adooooooooore cette fonction <3
        if (trim($detailUtilisateur[0]) == trim($user)) {
          //on initialise les variables
          $profilTrouve = true;
          $i = 1;
          //on regarde si le profil sélectionné à déjà été vu par l'utilisateur
          while (($i <= count($detailUtilisateur) - 1) && !$visiteurTrouve) {
            //print_r($detailUtilisateur);
            //strpos retourne l'index de la valeur recherchée ou "false" si elle n'apparait pas dans la chaîne
            //si c'est le cas alors on incrémente le nombre de visites
            if (strpos(trim($detailUtilisateur[$i]), trim($_SESSION["pseudo"])) !== false) {
              //on sépare les données de l'utilisateur
              $sousDetails = explode("|", $detailUtilisateur[$i]);
              //intval permet de retourner la valeur en "int" d'une chaine de caractères
              $sousDetails[1] = intval($sousDetails[1]) + 1; //on incrémente
              //implode concatenne les cases d'un tableau avec comme séparateur ce qu'il y a entre les guillemets
              //on rassemble le tableau en chaine de caractere
              $detailUtilisateur[$i] = implode("|", $sousDetails);
              //on arrête la recherche
              $visiteurTrouve = true;
            }
            $i++;
          }
          //on rassemble ici aussi
          $tmpUtilisateurEnLigne = trim(implode("§", $detailUtilisateur));
          //si l'utilisateur n'as jamais visité le profil alors on l'ajoute
          if (!$visiteurTrouve) {
            $tmpUtilisateurEnLigne = trim($tmpUtilisateurEnLigne) . "§" . trim($_SESSION["pseudo"]) . "|1";
          }
          if ($profilTrouve) {
            $k = 0;
            $lineFound = false;
            while (($k < count($utilisateurEnLigne)) && (!$lineFound)) { // empêche le conflit de remplacement en début de ligne
              if (startsWith($utilisateurEnLigne[$k], $user)) {
                $utilisateurEnLigne[$k] = $tmpUtilisateurEnLigne;
                $lineFound = true;
              }
              $k++;
            }
            $contentBis = implode("\n", $utilisateurEnLigne);
            file_put_contents("../register/data/matchs.txt", $contentBis);
          }
        }
        $a++; // on incrémente
      }
      //on récupère les contenus des utilisateurs
      $contenu_du_fichierUserList = file_get_contents('../register/data/userList.txt');
      //on met chaque ligne dans un tableau
      $nbrUser = explode("\n", $contenu_du_fichierUserList);
      $j = 0;
      $i = 0;
      $fin = false;
      $banni = true;
      /*on lit le tableau (donc le fichier text ligne par ligne)
jusqu'à ce qu'on ait trouvé un identifiant correspondant
ou jusqu'à la fin du tableau*/
      while (($j < count($nbrUser)) && (!$fin)) {
        /*on met ce qui est entre les § dans des cases d'un tableau afin de pouvoir
  récupérer les différentes données présentes dans chaque ligne*/
        $donnee = explode("§", $nbrUser[$j]);
        /*on regarde si l'identifiant dans la ligne en cour est le bon*/
        if ($donnee[0] == $user) {
          /*si c'est le cas on passe fin a true pour arréter la recherche*/
          $fin = true;
          if($donnee[13]!="banned"){
          $banni = false;
          while (($i < count($donnee) - 1)) {
            /*on fait un tableau de tableau : on reprend le tableau séparer selon
    les § et on le sépare à nouveaux selon les | on pourra donc
    récupérer les différentes données en faisant $donneeBis[$i][$j]*/
            $donneeBis[$i] = explode("|", $donnee[$i]);
            $i++;
          }
          //Données modifiables :
      ?>
          <div id="Infos">

            <div id="partGauche">
              <div id="BlocInfo"> <!--photo-->
              <h2>Photos !</h2>
              <ul>
              <?php if (afficher($donneeBis, 12, 0)) { ?>
              <li><img src="<?php echo ($donneeBis[12][0]); ?>"></img></li>
              <?php } else {
                echo "Aucune photo disponible... :(";
              } if (afficher($donneeBis, 12, 1)) { ?>
              <li><img src="<?php echo ($donneeBis[12][1]); ?>"></img></li>
            <?php }if (afficher($donneeBis, 12, 2)) { ?>
              <div class="clear"></div>
              <li><img src="<?php echo ($donneeBis[12][2]); ?>"></img></li>
            <?php }if (afficher($donneeBis, 12, 3)) { ?>
              <li><img src="<?php echo ($donneeBis[12][3]); ?>"></img></li>
            <?php } ?>
            </ul>
            <div class="clear">

            </div>
          </div> <!--fin photo-->

            </div>

            <div id="partDroite">
              <div id="BlocInfo"> <!--info générales-->
                <h2>Informations Générales :</h2>
                <ul>
                  <!--On ecrit chaque donnée avec soit donnee[$i] si la donnée de
          contient pas de | soit avec donneeBis[$i][$j] si elle en contient.
        Puis on stock la donnée dans une variable de session pour pouvoir la réutiliser-->
                  <li>Pseudo : <?php echo ($donnee[0]);?></li>
                  <?php if (afficher($donneeBis, 1, 0)) { ?>
                    <li>Lieu de résidence : <?php echo ($donnee[1]);?></li>
                  <?php } ?>
                  <li>Sexe : <?php echo ($donnee[2]);?></li>
                  <li>Date de naissance : <?php echo ($donnee[3]);?></li>
                  <?php if (afficher($donneeBis, 4, 0)) { ?>
                    <li>Profession : <?php echo ($donnee[4]);?></li>
                  <?php } ?>
                  <li>Situation amoureuse : <?php echo ($donneeBis[5][0]);?></li>
                  <?php if (($donneeBis[5][1] == "1") || ($donneeBis[5][1] == "2") || ($donneeBis[5][1] == "3-5") || ($donneeBis[5][1] == "5+")) { ?>
                    <li>Nombre d'enfants : <?php echo ($donneeBis[5][1]);?></li>
                    <?php } elseif ($donneeBis[5][1] == "-1") { ?>
                  <li>Nombre d'enfants : "non-mentionné" </li>
                <?php } ?>
                </ul>
              </div>

              <div id="BlocInfo"> <!--info physiques-->
                <h2>Informations physiques :</h2>
                <ul>
                  <li>Poids : <?php echo ($donneeBis[6][0]);?> kg</li>
                  <li>Taille : <?php echo ($donneeBis[6][1]);?> cm</li>
                  <li>Couleur des cheveux : <?php echo ($donneeBis[6][2]);?></li>
                  <li>Couleur des yeux : <?php echo ($donneeBis[6][3]);?></li>
                </ul>
              </div> <!--fin info physique-->


              <div id="BlocInfo"> <!--info profil-->
                <h2>Informations profil :</h2>
                <ul>
                  <?php if (afficher($donneeBis, 7, 0)) { ?>
                    <li>Message d'accueil : <?php echo ($donnee[7]);?></li>
                    <?php } if (afficher($donneeBis,8,0)){ ?>
                    <li>Citation : <?php echo ($donnee[8]);?></li>
                    <?php } if (afficher($donneeBis,9,0)){ ?>
                    <li>Interets : <?php echo ($donnee[9]);?></li>
                  <?php }
                  if ($donnee[10] == "on") { ?>
                    <li>Fumeur ? : <?php echo ("oui");?></li>
                  <?php }
                  if (($donnee[11][0] == "1") || ($donnee[11][0] == "2") || ($donnee[11][0] == "3+")) { ?>
                    <li>Nombre de chiens : <?php echo ($donneeBis[11][0]);?></li>
                    <li>Infos chiens : <?php echo ($donneeBis[11][1]);?></li>
                  <?php } ?>
                </ul>
              </div>
            </div> <!--fin info profil-->
            </div>


    <?php
        }
      }
        //on passe à la ligne suivante
        $j++;
      }
      if (!$fin) {
        echo ("<h1>Une erreur s'est produite, ce profil n'existe pas.</h1>");
      }elseif ($banni) {
        echo ("<h1>Cet utilisateur a été banni.</h1>");
      }
    } else {
      echo ("Une erreur s'est produite, ce profil n'existe pas.");
    }
    ?>
    <div class="clear"></div>
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
} else {
  header("Location: /home/accueil.php");
}
?>
