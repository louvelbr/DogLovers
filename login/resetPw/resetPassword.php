<?php
session_start();
if (!(isset($_SESSION["login_Type"]))) { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <title>Dog Lovers - Réinitialiser le mot de passe</title>
        <link rel="stylesheet" type="text/css" href="./reset.css">
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
    

     /*  VERIFICATION FORMULAIRE */

    $_SESSION["adresseM"] = $_SESSION["passworded"] = $_SESSION["Newpassword"] = $_SESSION["PseudoReset"] = $erreurPseudoReset = $erreurAdresseM = $erreurNewPassword = "";

    $adresseOk = $NewpasswordOk = $PseudoResetOk = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["adresseM"])) {
        $erreurAdresseM = "Le champ adresse est requis";
    } else {
        $adresseOk = true;
        $_SESSION["adresseM"] = test_input($_POST["adresseM"]);
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $_SESSION["adresseM"])) {
            $erreurAdresseM = "L'adresse mail est invalide.";
            $adresseOk = false;
        }
    }
    if (empty($_POST["Newpassword"])) {
        $erreurNewPassword = "Le champ nouveau mot de passe est requis";
    } else {
        $NewpasswordOk = "true";
        $_SESSION["Newpassword"] = test_input($_POST["Newpassword"]);
        if (!preg_match("/[^§\s]+/", $_SESSION["Newpassword"])) {
            $erreurNewPassword = "Le mot de passe est invalide.";
            $NewpasswordOk = false;
        }
    }
        if (empty($_POST["PseudoReset"])) {
            $erreurPseudoReset = "Le champ nouveau mot de passe est requis";
        } else {
            $PseudoResetOk = "true";
            $_SESSION["PseudoReset"] = test_input($_POST["PseudoReset"]);
            if (!preg_match("/[^§\s]+/", $_SESSION["PseudoReset"])) {
                $erreurPseudoReset = "Le pseudo est invalide.";
                $PseudoResetOk = false;
            }
    }
    if ($adresseOk && $NewpasswordOk && $PseudoResetOk) { // confirmation données
        $_SESSION["passworded"] = "true"; 
        header("Location: /login/resetPw/reset.php"); // envoi des données
    }
}
    ?>

    <body>
        <div id="bloc_Image_reset">
          <div id="part_logo">
            <a href="../login.php"><img src="/ressources/dogloverslogo.png" alt="logo"></img></a>

          </div>

            <div id="oubliage">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="PseudoReset">Pseudo</label><br>
                    <input name="PseudoReset" type="text" pattern="[^\s§]+" value="" placeholder="Pseudo" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et ; ")' oninput="setCustomValidity('')" required /> <br>
                <label for="adresseM">Adresse Mail</label><br>
                    <input name="adresseM" type="text" pattern="[^\s§]+" value="" placeholder="Adresse Mail" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et ; ")' oninput="setCustomValidity('')" required /> <br>
                    <span> <?php echo $erreurAdresseM ?></span>
                    <label for="password">Mot de Passe</label><br>
                    <input name="Newpassword" type="password" pattern="[^\s§]+" value="" placeholder="Nouveau mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et § ")' oninput="setCustomValidity('')" required /> <br>
                    <span> <?php echo $erreurNewPassword ?></span> <br>

                    <input type="submit" value="Réinitialiser"></input>
                </form>
                <span><?php
                        if (isset($_SESSION["erreur"]) &&  $_SESSION["erreur"] == "badMail") {
                            echo "Adresse Mail ou Pseudo inconnu.";
                            unset($_SESSION["erreur"]);
                        } else if (isset($_SESSION["erreur"]) &&  $_SESSION["erreur"] == "resetConfirmed") {
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
