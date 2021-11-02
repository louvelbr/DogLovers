<?php
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) > 0)) { ?>
  <!DOCTYPE html>
  <html>
  <?php
  function getDateFinAbonnement(string $username): string
  {
    $path = "./../../register/data/userList.txt";
    $file = fopen($path, 'r');
    if ($file) {
      $lastvalue = true;
      while ((($line = fgets($file)) !== false) && $lastvalue) { 
        $userData = explode("§", $line);
        if ((trim($_SESSION["pseudo"]) == trim($userData[0]))) {
          $tmpdate = explode(':', trim($userData[sizeof($userData) - 6])); // récupère la date de fin d'abonnement
          $date = $tmpdate[1];
          $lastvalue = false;
        }
      }
      fclose($file);
    } else {
      phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
    }
    return $date;
  }
  ?>

  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Accueil</title>
    <link rel="stylesheet" type="text/css" href="./subscribe.css">
    <link rel="shortcut icon" href="./../../ressources/favicon.ico" />
    <script src="./subscribe.js"></script>
  </head>

  <body>
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Accueil</h1>
    </div>
    <div class="menu">
      <ul>
        <li><a  href="/home/accueil.php">Accueil</a></li>
        <li><a  href="/profil/monProfil/MonProfil.php">Mon profil</a></li>
        <li><a  class="active" href="/home/subscribe/subscribe.php">Gérer l'abonnement</a></li>
        <li class="deconnexion"><a href="/login/logout.php">Deconnexion</a></li>
      </ul>
    </div>

    <div id="page">
      <h2 id='titreTab'> Formules d'abonnement</h2>

      <div id="BlocInfo">

        <div class="partieGauche">
          <span id="titreInfo"> Gratuit</span> <br>
          <div id="textGratuit">
            <?php
            if (intval($_SESSION["login_Type"]) == 1) {
              echo "Vous avez actuellement un compte gratuit.";
            } else if ($_SESSION["login_Type"] != 3) {
              echo ' <a href="./confirmSubscription.php?abonnement=cancel"><input type="button" id="bouton2" value="Se désabonner !"></a>';
             }
            ?> </div> <br>
        </div>

        <div class="partieDroite">
          <span id="titreInfo"> Abonné </span> <br>
          <div id="textAbonne"> <?php if (intval($_SESSION["login_Type"] == 2)) {
                    echo "Vous êtes abonné(e) jusqu'au " . getDateFinAbonnement($_SESSION["pseudo"]) . " !";
                  } else if ($_SESSION["login_Type"] != 3) { ?>
                    <input type="button" id="bouton1" value="Consulter les formules d'abonnement !" onclick ="displaySubMode()"> <br>
                    <div id="listeabonnements">
                    <a href="./confirmSubscription.php?abonnement=48h"><input type="button" id="bouton3" value="S'abonner pour 48hrs !"></a>
                    <span>Au prix réduit de 0.99€ !</span> <br>
                    <a href="./confirmSubscription.php?abonnement=1mo"><input type="button" id="bouton3" value="S'abonner pour 1 mois !"></a>
                    <span>Au prix de 6.99€ !</span> <br>
                    <a href="./confirmSubscription.php?abonnement=6mo"><input type="button" id="bouton3" value="S'abonner pour 6 mois !"></a>
                    <span>Au prix de 29.99€ (soit 4.99 par mois) !</span> <br>
                    </div>
                  <?php } else {
                    echo "En tant qu'administrateur vous êtes abonné à vie.";
                  }
                  ?> </div> <br>
        </div>
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
            <h4>Nous contacters</h4>
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
  header('Location: ./../errors/erreur403.php');
}
?>
