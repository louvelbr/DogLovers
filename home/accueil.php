<?php
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) > 0)) { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Accueil</title>
    <link rel="stylesheet" type="text/css" href="./accueil.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
    <script type="text/javascript" src="./recherche/recherche.js"></script>
  </head>

  <body>
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Accueil</h1>
    </div>
    <script>
    // fonction ajax relative à la recherche dynamique
      function showResult(str) {
        if (str.length == 0) {
          document.getElementById("resultats").innerHTML = "";
          document.getElementById("resultats").style.visibility = "hidden";
          return;
        }
        var xmlhttp = new XMLHttpRequest(); // création de la requete
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) { // si la page est prete
            document.getElementById("resultats").innerHTML = this.responseText;
            document.getElementById("resultats").style.visibility = "visible";
          }
        }
        xmlhttp.open("GET", "./recherche/recherche.php?recherche=" + str, true); // on ouvre la requete
        xmlhttp.send();
      }
    </script>
    <div class="menu">
      <ul>
        <li><a class="active" href="./accueil.php">Accueil</a></li>
        <li><a  href="../profil/monProfil/MonProfil.php">Mon profil</a></li>
        <li><a  href="/home/subscribe/subscribe.php">Gérer l'abonnement</a></li>
        <li><a  href="../messagerie/messagerieGenerale.php">Mes messages</a></li>
        <?php if ($_SESSION["login_Type"] == 3) { echo '<li><a  href="/admin/membres.php">Liste des utilisateurs</a></li>'; // si l'utilisateur est admin
        echo '<li><a  href="/admin/reports/listeReports.php">Liste des signalements</a></li>';
        echo  '<li><a href="/admin/conversations.php">Liste des Conversations</a></li>';}?>
        <li class="deconnexion"><a href="./../login/logout.php">Deconnexion</a></li>
        <input type="checkbox" name="r" id="r1">
        <label for="r1" class="bar"><img src="./../ressources/loupe.png" alt="img_loupe" class="rounded-corners" onclick="changeVisibility('resultats')"></label>
        <li class="formulaireee">
          <div class="slides">
            <div class="slide s1"><!--vide-->
            </div>
            <div class="slide">
              <form action="./recherche/searchPage.php" method="get"><input class="searchbar" name="recherche" type="text" value="" onkeyup="showResult(this.value)" placeholder="(Recherche.....)" />
              <div id="resultats"></div>
              </form>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div id="page">
      <h2 id='titreTab'> Bonjour <?php echo htmlspecialchars($_SESSION["pseudo"]); ?> !</h2>
      <div id="BlocInfo">
        <span id="titreInfo">Liste de nos derniers membres :</span><br><br> 
        <?php
        if (isset($_SESSION["memberShipExpired"]) && ($_SESSION["memberShipExpired"] == "true")) {
          echo "<span id='memberShipExpired'> Votre abonnement à expiré, pour vous réabonner, cliquez ici : </span>";
          echo "<br> <a href='/home/subscribe/subscribe.php'><input type='button' value='Se réabonner !'></a>";
          unset($_SESSION["memberShipExpired"]);
        }
        ?>
          <?php 
             $path = "./../register/data/userList.txt"; // chemin du fichier des utilisateurs
             $content = file_get_contents($path); // récupère les données du fichier
             $user = array(); 
             $usersrc = array();
             $content = explode("\n",$content); // récupère les données user
             $content = array_filter($content); // efface les champs vides (le explode \n génère une dernière case d'array vide.)
             $size = sizeof($content) > 20 ? 20 : sizeof($content); // permet de générer dynamiquement la liste des personnes
             for ($i=0; $i < $size; $i++) { 
               $tmp = explode("§",$content[$i]);  // séparation données de l'utilisateur 
               $tmptmp = explode("|",$tmp[sizeof($tmp)-7]); // récupération images 
               array_push($user,$tmp[0]); // ajout du pseudo de l'utilsiateur à la liste des données
               $yee = !empty($tmptmp[0]) ? $tmptmp[0] : "/ressources/dogloverslogo.png" ; // assigne la référence d'une image ou une image par défaut selon si l'utilisateur possède un image de profil ou non
               array_push($usersrc,$yee); // ajout de la source de l'image de l'utilsiateur à la liste des données
             } ?>
                <?php 
                for ($i=0; $i < $size; $i++) { 
                if (trim($user[$i]) != $_SESSION["pseudo"]) {?> <!-- Empeche l'affichage de l'utilisateur actuellement connecté dans la liste des derniers membres -->
               <div class="divUtilisateur"><a <?php echo 'href="/profil/profil.php?user='. $user[$i] .'">';?><span class="nomPersonne"><?php echo $user[$i]; ?> </span> <img src="<?php echo $usersrc[$i]; ?>" alt="Nom <?php echo $i; ?>" width="200" height="200" /></a></div>
              <?php } } ?>
      </div>
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
          <p>DogLover - Car les animaux sont nés pour nous apprendre à aimer.</p>
          <br>
          &copy; DogLovers - Tout droits réservés.
        </div>
      </div>
    </footer>
  </html>
<?php

}else{
  header('Location: ./../errors/erreur403.php');
}
?>
