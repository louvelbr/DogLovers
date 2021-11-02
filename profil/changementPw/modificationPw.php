<?php
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) > 0)) { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <title>Dog Lovers - Modifier son Mot de passe</title>
        <link rel="stylesheet" type="text/css" href="./modificationPw.css">
        <link rel="shortcut icon" href="./../ressources/favicon.ico" />
    </head>
    <?php

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

    /* VERIFICATIONS FORMULAIRE */ 

    $_SESSION["OldPw"] = $_SESSION["changePw"] = $_SESSION["Newpassword"] = $erreurOldPw  = $erreurNewPassword = "";

    $NewpasswordOk = $OldPwOk = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["Newpassword"]) || empty($_POST["confirmNewPassword"])) {
        $erreurNewPassword = "Les champs  mot de passe sont requis.";
    } else {
        $NewpasswordOk = "true";
        $tmp = test_input($_POST["confirmNewPassword"]);
        $_SESSION["Newpassword"] = test_input($_POST["Newpassword"]);
        if (!preg_match("/[^§\s]+/", $_SESSION["Newpassword"]) || (!preg_match("/[^§\s]+/",$tmp)) || ($tmp != $_SESSION["Newpassword"])) {
            $erreurNewPassword = "Le mot de passe est invalide ou les mot de passe ne correspondent pas.";
            $NewpasswordOk = false;
        }
    }
        if (empty($_POST["OldPw"])) {
            $erreurOldPw = "Le champ ancien mot de passe est requis";
        } else {
            $OldPwOk = "true";
            $_SESSION["OldPw"] = test_input($_POST["OldPw"]);
            if (!preg_match("/[^§\s]+/", $_SESSION["OldPw"])) {
                $erreurOldPw = "L'ancien mot de passe est invalide.";
                $OldPwOk = false;
            }
    }
    if ($NewpasswordOk && $OldPwOk) {
        $_SESSION["changePw"] = "true";
        header("Location: ./confirmModificationPw.php");
    }
}
    ?>

    <body>
        <div id="bloc_Image_reset">
          <div id="part_logo">
            <a href="./../monProfil/MonProfil.php"><img src="/ressources/logoBis.png" alt="logo" class="rounded-corners"></img></a>
          </div>

            <div id="oubliage">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="OldPw">Ancien mot de passe</label><br>
                    <input name="OldPw" type="password" pattern="[^\s§]+" value="" placeholder="Ancien mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et §")' oninput="setCustomValidity('')" required /> <br>
                    <span> <?php echo $erreurOldPw; ?></span>
                    <br>
                <label for="Newpassword">Nouveau mot de passe</label><br>
                    <input name="Newpassword" type="password" pattern="[^\s§]+" value="" placeholder="Nouveau mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et §")' oninput="setCustomValidity('')" required /> <br>
                    <span> <?php echo $erreurNewPassword ?></span>
                    <br>
                    <label for="confirmNewPassword">Confirmez mot de passe</label><br>
                    <input name="confirmNewPassword" type="password" pattern="[^\s§]+" value="" placeholder="Confirmez le mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et § ")' oninput="setCustomValidity('')" required /> <br>
                    <br>
                    <input type="submit" value="Réinitialiser"></input>
                </form>
                <span><?php
                        if (isset($_SESSION["erreur"]) &&  $_SESSION["erreur"] == "badMail") { // erreur , mismatch avec le mot de passe actuel
                            echo "Ancien mot de passe incorrect.";
                            unset($_SESSION["erreur"]);
                        } else if (isset($_SESSION["erreur"]) &&  $_SESSION["erreur"] == "resetConfirmed") { // confirmation de réinitialisation
                            echo "Mot de passe réinitialisé!.";
                            unset($_SESSION["erreur"]);
                        }
                        ?></span>
            </div>
        </div>
    </body>

    </html>
<?php
} else
    header("Location: /errors/erreur403.php")
?>
