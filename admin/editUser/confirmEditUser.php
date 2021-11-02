<?php
session_start();

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

if (isset($_SESSION["confirmEdit"]) && ($_SESSION["confirmEdit"] == "true")) { // vérification demande de réinitilisation de pw
    $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
    $file = fopen($path, 'r'); // ouverture du fichier
    if ($file) { // si le fichier est bien ouvert alors
        $lastvalue = true; 
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
            $userData = explode("§", $line); // séparation des données de la ligne utilisateur
            //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
            if ((trim($_SESSION["userToEdit"]) == trim($userData[0]))) { // si l'adresse mail entrée correspond a une adresse mail en bdd alors 
                $contents = file_get_contents($path);
                $userData[7] = $_SESSION["editMsgAcc"]; // change les données modifiables
                $userData[8] = $_SESSION["editCitation"]; 
                $userData[9] = $_SESSION["editInterets"];
                $userData = implode("§",$userData);
                $contents = str_replace($line,$userData,$contents);
                file_put_contents($path, $contents);
                $lastvalue = false;
            }
        }
        fclose($file);
    } else {
        phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
    }
    
    if ($lastvalue) {
        unset($_SESSION["confirmEdit"]);
        $_SESSION["erreur"] = "erreur";
        header("Location: ./editUser.php");
    } else {
        unset($_SESSION["confirmEdit"]);unset($_SESSION["editMsgAcc"]);unset($_SESSION["editCitation"]);unset($_SESSION["editInterets"]);unset($_SESSION["userToEdit"]); // unset les vars utilisées
        header("Location: /admin/membres.php");
    }
    
} else {
    header("Location: /errors/erreur403.php");
}
?>