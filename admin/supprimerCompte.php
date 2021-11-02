<?php
//on démarre une session
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 3)) { ?>
  <!DOCTYPE html>
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Supprimer un compte</title>
    <link rel="stylesheet" type="text/css" href="bannir/bannir.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
  </head>

  <body>
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Supprimer</h1>
    </div>
    
    <?php

    // Merci stack Overflow - https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    function startsWith($haystack, $needle)
    {
      $length = strlen($needle);
      return (substr($haystack, 0, $length) === $needle);
    }

  function removePic(string $photo)
  {
    $photo = explode("|", $photo);
    for ($i=0; $i < 4; $i++) {
    if (!empty($photo[$i])) {
      $response = unlink("./.." . $photo[$i]); // supprime la photo associée l'indice 'i' si elle existe
    }
    }
  }

  function resetMatches(string $nomuser)
  {
    $path = "./../register/data/matchs.txt"; // chemin fichier utilisateur
    $contenu = file_get_contents($path);
    $contenuLigne = explode("\n", $contenu);
    for ($i=0; $i < (sizeOf($contenuLigne)-2); $i++) {
      if (startsWith($contenuLigne[$i], $nomuser)) {
        $contenuLigne[$i] = ""; // supprime les données utilisateur du fichier matchs
      } else if (strrpos($contenuLigne[$i], $nomuser) !== false) { // cherche si l'utilisateur apparait comme "visiteur" sur un profil
        $part2 = explode("§", $contenuLigne[$i]);
        for ($j=1; $j < sizeOf($part2); $j++) {
          if (strrpos($part2[$j], $nomuser) !== false) {
            $part2[$j] = ""; // efface l'utilisateur des visiteurs
            $part2 = array_filter($part2);
          }
        }
        array_filter($part2);
        $contenuLigne[$i] = implode("§",$part2); 
      }
    }
    $contenuLigne = array_filter($contenuLigne);
    $contents = implode("\n",$contenuLigne);
    file_put_contents($path,$contents); // rassemble les données et retour dans le fichier
  }
  
      $user = $_GET["user"];
      $lastvalue = true;
      $path = "./../register/data/userList.txt"; // chemin fichier utilisateur
      $file = fopen($path, 'r'); // ouverture du fichier
      if ($file) { // si le fichier est bien ouvert alors
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
          $userData = explode("§", $line); // séparation des données de la ligne utilisateur
          if ($userData[0] == $user) {
            $contents = file_get_contents($path);
            removePic($userData[sizeof($userData) - 7]); // supprimes les photos 
            resetMatches(trim($userData[0])); // supprime les données du fichier matchs.txt
            $userData = "";
            $contents = str_replace($line, $userData, $contents); // supprime les données dans le fichier userList.txt
            file_put_contents($path, $contents);
            $lastvalue = false;
          }
        }
        fclose($file);
      }
    //Données modifiables :
    ?>
    <div class="menu">
      <ul>
        <li><a href="../home/accueil.php">Retour à l'accueil</a></li>
      </ul>
    </div>
    <?php
    if (!$lastvalue) { ?>
      <h1>Vous avez supprimé le compte de <?php echo $user; ?>!</h1>
    <?php } else { ?>
      <h1>Vous ne pouvez pas supprimer le compte de <?php echo $user; ?> ou cet utilisateur n'existe pas !</h1>
    <?php } ?>
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