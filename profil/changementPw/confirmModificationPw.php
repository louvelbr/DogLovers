<?php
session_start();

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

if (isset($_SESSION["changePw"]) && ($_SESSION["changePw"] == "true")) { // vérification demande de réinitilisation de pw
    $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
    $file = fopen($path, 'r'); // ouverture du fichier
    if ($file) { // si le fichier est bien ouvert alors
        $lastvalue = true; 
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
            $userData = explode("§", $line); // séparation des données de la ligne utilisateur
            //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
            if ((password_verify(trim($_SESSION["OldPw"]), trim($userData[sizeof($userData) - 1]))) && (trim($_SESSION["pseudo"]) == trim($userData[0]))) { // si l'adresse mail entrée correspond a une adresse mail en bdd alors 
                $contents = file_get_contents($path);
                $userData[sizeof($userData)-1] = password_hash($_SESSION['Newpassword'],PASSWORD_DEFAULT);
                $userData = implode("§",$userData);
                $contents = str_replace($line,$userData . "\n",$contents);
                file_put_contents($path, $contents);
                $lastvalue = false;
            }
        }
        fclose($file);
    } else {
        phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
    }
    unset($_SESSION["changePw"]);unset($_SESSION["newPw"]);unset($_SESSION["OldPw"]);
    if ($lastvalue) {
        $_SESSION["erreur"] = "badMail";
    } else {
        $_SESSION["erreur"] = "resetConfirmed";
    }
    header("Location: ./modificationPw.php");
} else {
    header("Location: /errors/erreur403.php");
}
?>