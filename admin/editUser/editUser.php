<?php
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 3)) { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <title>Dog Lovers - Modifier un Profil</title>
        <link rel="stylesheet" type="text/css" href="./editUser.css">
        <link rel="shortcut icon" href="./../ressources/favicon.ico" />
    </head>
    <?php

    if (($_SERVER["REQUEST_METHOD"] == "GET") && isset($_GET["userToEdit"])) {
        $_SESSION["userToEdit"] = $_GET["userToEdit"];
    } else if (!isset($_SESSION["userToEdit"])) {
        $_SESSION["userToEdit"] = "Erreur";
    }

    function phpAlert($msg) {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function initData()
    {
        $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
        $file = fopen($path, 'r'); // ouverture du fichier
        if ($file) { // si le fichier est bien ouvert alors
            $lastvalue = true;
            while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
                $userData = explode("§", $line); // séparation des données de la ligne utilisateur
                //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
                if (trim($_SESSION["userToEdit"]) == trim($userData[0])) { // si l'adresse mail entrée correspond a une adresse mail en bdd alors
                    $_SESSION["editMsgAcc"] = $userData[7];
                    $_SESSION["editCitation"] = $userData[8];
                    $_SESSION["editInterets"] = $userData[9];
                    $lastvalue = false;
                }
            }
            fclose($file);
        } else {
            phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
        } if ($lastvalue) {
                    $_SESSION["editMsgAcc"] = "Erreur";
                    $_SESSION["editCitation"] = "Erreur";
                    $_SESSION["editInterets"] = "Erreur";
        }
    }

    initData();
                /* Vérification données formulaire */
    $_SESSION["confirmEdit"] = $erreurInterets  = $erreurCitation = $erreurMsgAcc ="";

    $msgAccFilled = $citationFilled = $interetFilled = true;

    if (($_SERVER["REQUEST_METHOD"] == "POST")) {



        if (empty($_POST["editMsgAcc"])) {
            $_SESSION["editMsgAcc"] = "";
        } else {
            $_SESSION["editMsgAcc"] = test_input($_POST["editMsgAcc"]);
            if (preg_match("/[^a-zA-Z ';,.\-!:?éàôöîïùêèç]+/", $_SESSION["editMsgAcc"])) {
                $erreurMsgAcc = "Le message d'accueil est invalide.";
                $msgAccFilled = false;
            }
        }

        if (empty($_POST["editCitation"])) {
            $_SESSION["editCitation"] = "";
        } else {
            $_SESSION["editCitation"] = test_input($_POST["editCitation"]);
            if (preg_match("/[^a-zA-Z ';,.\-!:?éàôöîïèùêç]/", $_SESSION["editCitation"])) {
                $erreurCitation = "La citation est invalide.";
                $citationFilled = false;
            }
        }

        if (empty($_POST["editInterets"])) {
            $_SESSION["editInterets"] = "";
        } else {
            $_SESSION["editInterets"] = test_input($_POST["editInterets"]);
            if (preg_match("/[^a-zA-Z ';,.\-!:?éàôöîïèêùç]+/", $_SESSION["editInterets"])) {
                $erreurInterets = "Les centres d'interets sont invalides.";
                $interetFilled = false;
            }
        }
    if ($interetFilled && $citationFilled && $msgAccFilled) {
        $_SESSION["confirmEdit"] = "true";
        header("Location: ./confirmEditUser.php");
    }
}
    ?>

    <body>
        <div id="bloc_Image_reset">
          <div id="part_logo">
            <a href="/admin/membres.php"><img src="/ressources/logoBis.png" alt="logo" class="rounded-corners"></img></a>
          </div>

            <div id="oubliage">
                <h1>Edition en tant qu'Admin de la page de <?php if (isset($_SESSION["userToEdit"])) { echo $_SESSION["userToEdit"];}?></h1>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="editMsgAcc">Editer le message d'accueil</label><br>
                    <input name="editMsgAcc" type="text" pattern="[^§]+" value="<?php echo $_SESSION["editMsgAcc"]?>" placeholder="Message d'accueil" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser § ")' oninput="setCustomValidity('')" /> <br>
                    <span> <?php echo $erreurMsgAcc; ?></span>
                <label for="editCitation">Editer la citation</label><br>
                    <input name="editCitation" type="text" pattern="[^§]+" value="<?php echo $_SESSION["editCitation"]?>" placeholder="Citation" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utilsier § ")' oninput="setCustomValidity('')" /> <br>
                    <span> <?php echo $erreurCitation ?></span>
                    <label for="editInterets">Editer les interets</label><br>
                    <input name="editInterets" type="text" pattern="[^§]+" value="<?php echo $_SESSION["editInterets"]?>" placeholder="Interets" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser  § ")' oninput="setCustomValidity('')" /> <br>
                    <span> <?php echo $erreurInterets ?></span>

                    <input type="submit" value="Modifier"></input>
                </form>
                <span><?php
                        if (isset($_SESSION["erreur"]) &&  $_SESSION["erreur"] == "erreur") {
                            echo "Une erreur s'est produite";
                            unset($_SESSION["erreur"]);
                        }
                        ?></span>
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
          <br>
          &copy; DogLovers - Tout droits réservés.
        </div>
      </div>
    </footer>
    </html>
<?php
} else
    header("Location: /errors/erreur403.php")
?>
