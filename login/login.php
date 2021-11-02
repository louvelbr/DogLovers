<?php
session_start();
if (!(isset($_SESSION["login_Type"])) || ((isset($_SESSION["logout"])) && ($_SESSION["logout"] == "success")) || ((isset($_SESSION['banned'])) && ($_SESSION['banned']=="true"))) { ?>
<!DOCTYPE html>
<html>

  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Le site de rencontre pour les amoureux des chiens.</title>
    <link rel="stylesheet" type="text/css" href="./login.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico"/> <!--??-->
  </head>

  <body>

    <div id="part_logo"> <!--Partie logo-->
      <img src="./../ressources/logoBis.png" alt="logoBis" class="rounded-corners"></img>
    </div> <!--Fin partie logo-->



    <div id="part_centre"> <!--Partie centrale-->

      <div class="header_part_centre">
        Voici la liste de nos derniers inscrits :
      </div>

      <div id="bloc_Noms"> <!--Bloc slide image-->

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script type="text/javascript">
          $(function(){
            setInterval(function(){
              $(".slideshow ul").animate({marginLeft:-200},800,function(){
              $(this).css({marginLeft:0}).find("li:last").after($(this).find("li:first")); 
              })
            }, 3500);
          });
        </script>

          <?php
          $path = "./../register/data/userList.txt"; // chemin du fichier des utilisateurs
          $content = file_get_contents($path); // récupère les données du fichier
          $user = array(); 
          $usersrc = array();
          $content = explode("\n",$content); // récupère les données user
          $content = array_filter($content); // efface les champs vides (le explode \n génère une dernière case d'array vide.)
          for ($i=1; $i < 5; $i++) { 
            $tmp = explode("§",$content[sizeof($content)-$i]);  // séparation données de l'utilisateur 
            $tmptmp = explode("|",$tmp[sizeof($tmp)-7]); // récupération images 
            array_push($user,$tmp[0]); // ajout du pseudo de l'utilsiateur à la liste des données
            $yee = !empty($tmptmp[0]) ? $tmptmp[0] : "/ressources/dogloverslogo.png" ; // assigne la référence d'une image ou une image par défaut selon si l'utilisateur possède un image de profil ou non
            array_push($usersrc,$yee); // ajout de la source de l'image de l'utilsiateur à la liste des données
          }
          ?>

        <div class="slideshow"> <!--liste des images-->
          <ul>
            <li><div class="Colonne"><?php echo $user[0]; ?><img src="<?php echo $usersrc[0]; ?>" alt="Nom 1" width="200" height="200" /></div></li>
            <li><div class="Colonne"><?php echo $user[1]; ?><img src="<?php echo $usersrc[1]; ?>" alt="Nom 2" width="200" height="200" /></div></li>
  		      <li><div class="Colonne"><?php echo $user[2]; ?><img src="<?php echo $usersrc[2]; ?>" alt="Nom 3" width="200" height="200" /></div></li>
            <li><div class="Colonne"><?php echo $user[3]; ?><img src="<?php echo $usersrc[3]; ?>" alt="Nom 4" width="200" height="200" /></div></li>
          </ul>
        </div><!--fin liste images-->

      </div> <!--Fin bloc slide image-->



      <div id="bloc_sign"> <!--Bloc connexion/inscription-->

        <input type="radio" name="r" id="r1" checked> <!--Reliés à la navigation-->
        <input type="radio" name="r" id="r2">

        <div class="slideshow2"> <!--Partie slide connexion/inscription-->

          <div class="slides"> <!--bloc contenant toutes les slides-->

            <div class="slide s1"> <!--slide connexion-->

              <div class="slide1_ecrit"> <!--partie écrit-->
                <div class="slide1_ecrit_msg">
                  Vous avez déjà un compte ?<br><br>
                  Connectez-vous !
                </div>

                <div class="mess_err_deco"><!--message d'erreur ou déconnexion-->
                  <?php
                  if((isset($_SESSION['error'])) && ($_SESSION['error']=='error')){
                    echo '<div id="loginError"> Identifiant ou mot de passe incorrect.</div>';
                  } else if ((isset($_SESSION['logout'])) && ($_SESSION['logout']=='success')) {
                    echo '<div id="logoutSuccess">Déconnecté avec succès !</div>';
                  }else if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 0)) {
                    echo'<div id="loginError">Vous avez été banni !</div>';
                  }
                  ?>
                </div><!--fin message d'erreur ou déconnexion-->

              </div><!--fin partie écrit-->

              <div class="slide1_signin"><!--partie connexion-->
                <form accept-charset="UTF-8" action="./verificationConnexion.php" method="post">
                  <input name="pseudo" type="text" pattern="[^\s§]+" value="" placeholder="Pseudo" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et ; ")' oninput="setCustomValidity('')" required /><br>
                  <input name="password" type="password" pattern="[^\s§]+" value="" placeholder="Mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et ; ")' oninput="setCustomValidity('')" required /><br>
                  <a id="goublie" title="Mot de passe oublié" href="./resetPw/resetPassword.php">J'ai oublié mon mot de passe.</a> <br>
                  <input type="submit" id="buttonSignin" value="Se connecter"></input>
                </form>


              </div> <!--fin partie connexion-->

            </div> <!--fin slide connexion-->



            <div class="slide"> <!--slide inscription-->

              <div class="slide2_ecrit"> <!--partie écrit-->
                Vous n'avez pas encore<br>
                de compte ?
              </div> <!--fin partie écrit-->

              <div class="slide2_inscription"> <!--partie inscription-->
                Créez votre compte
                <form action="./../register/register.php">
                  <input type="submit" id="buttonSignup" value="ICI"></input>
                </form>

              </div> <!--fin partie inscription-->

            </div> <!--fin slide inscription-->

          </div> <!--fin du bloc contenant toutes les slides-->

        </div> <!--Fin partie slide connexion/inscription-->

        <div class="navigation"> <!--Navigation-->
          <label for="r1" class="bar b1"></label>
          <label for="r2" class="bar b2"></label>
        </div> <!--Fin navigation-->

      </div><!--Fin bloc connexion/inscription-->

    </div> <!--Fin partie centrale-->

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
              <li><a href="login.php">Se connecter</a></li>
              <li><a href="/register/register.php">S'enregistrer</a></li>
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
          <p id="slogan">DogLover - Car les animaux sont nés pour nous apprendre à aimer.</p>
          <br>
          &copy; DogLovers - Tout droits réservés.
        </div>
      </div>
    </footer>
</html>

<?php
session_destroy();
session_unset();
} else {
  header('Location: ./../home/accueil.php');
} ?>
