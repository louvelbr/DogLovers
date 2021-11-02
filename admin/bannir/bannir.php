<?php
//on démarre une session
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 3)) { ?>
  <!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1−strict.dtd">
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Bannir un utilisateur.</title>
    <link rel="stylesheet" type="text/css" href="./bannir.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
  </head>

  <body>
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Banned</h1>
    </div>

    <?php

    $user = $_GET["user"];
    function remplacementData(array $userData): array // remplace le statut par "banned" - bannit l'utilisateur
    {
      $userData[13] = "banned";
      return $userData;
    }
    $lastvalue = true;
    if (trim($user) != trim($_SESSION["pseudo"])) {
      $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
      $file = fopen($path, 'r'); // ouverture du fichier
      if ($file) { // si le fichier est bien ouvert alors
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
          $userData = explode("§", $line); // séparation des données de la ligne utilisateur
          if ($userData[0] == $user) { // si l'utilisateur correspond
            $contents = file_get_contents($path);
            $userData = remplacementData($userData); // remplace la ligne
            $userData = implode("§", $userData);
            $contents = str_replace($line, $userData, $contents);
            file_put_contents($path, $contents);
            $lastvalue = false;
          }
        }
        fclose($file);
      }
    }
    ?>
    <div class="menu">
      <ul>
        <li><a href="/home/accueil.php">Retour à l'accueil</a></li>
      </ul>
      </ul>
    </div>

    <div class="messBannir">
      <?php
      if (!$lastvalue) { ?>
        <h1>Vous avez banni <?php echo $user; ?>!</h1>
      <?php } else { ?>
        <h1>Vous ne pouvez pas bannir <?php echo $user; ?> ou cet utilisateur n'existe pas !</h1>
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
} else {
  header("Location: /home/accueil.php");
}
?>
