<?php 
    session_start();

    function phpAlert($msg)
    {
      echo '<script type="text/javascript">alert("' . $msg . '")</script>';
    }

    function subAdd(string $toAdd, string $memberType):string
    {
        $tmp = explode(':',$memberType);
        $currentDate = (empty($tmp[1])) ? new DateTime(date("Y-m-d")) : new DateTime($tmp[1]); // génération d'une nouvelle date ou récupération de la date précédemment entrée (afin de l'incrémenter)
        $currentDate -> add(new DateInterval($toAdd)); // ajout de la date
        if ($tmp[0] == "free") { // changement de mode utilisateur
            $tmp[0] = "member";
        }
        $tmp[1] = $currentDate->format('Y-m-d'); // écriture de la date
        $data = implode(":", $tmp);
        return($data);
    }

    function addSubscription(string $toAdd)
    {
            $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
            $file = fopen($path, 'r'); // ouverture du fichier
            if ($file) { // si le fichier est bien ouvert alors
                $lastvalue = true;
                while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
                    $userData = explode("§", $line); // séparation des données de la ligne utilisateur
                    //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
                    if ((trim($_SESSION["pseudo"]) == trim($userData[0]))) { // si le pseudo correspond a un pseudo  en bdd alors 
                        $contents = file_get_contents($path); // récupération des données du fichier 
                        $userData[sizeof($userData)-6] = subAdd($toAdd,$userData[sizeof($userData)-6]); // mise à jour de l'abonnement
                        $userData = implode("§",$userData);
                        $contents = str_replace($line, $userData,$contents);  // ajout de la ligne dans les données
                        file_put_contents($path, $contents); // ajout des données au fichier
                        $lastvalue = false;
                    }
                }
                fclose($file);
            } else {
                phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
            }
    }

    function removeSub()
    {
        $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
            $file = fopen($path, 'r'); // ouverture du fichier
            if ($file) { // si le fichier est bien ouvert alors
                $lastvalue = true;
                while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
                    $userData = explode("§", $line); // séparation des données de la ligne utilisateur
                    //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
                    if ((trim($_SESSION["pseudo"]) == trim($userData[0]))) { // si le pseudo correspond a un pseudo  en bdd alors 
                        $contents = file_get_contents($path); // récupération des données du fichier 
                        $userData[sizeof($userData)-6] = "free";
                        $userData = implode("§",$userData);
                        $contents = str_replace($line, $userData,$contents);  // ajout de la ligne dans les données
                        file_put_contents($path, $contents); // ajout des données au fichier
                        $lastvalue = false;
                    }
                }
                fclose($file);
            } else {
                phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
            }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_SESSION["login_Type"])) && ($_SESSION["login_Type"] > 0) && (($_SESSION["login_Type"] !=3))) {
    $typeAbo = $_GET["abonnement"];
    switch ($typeAbo) {
        case '48h':
            addSubscription("P2D");
            $_SESSION["erreurAbo"] = "Merci d'avoir renouvelé votre abonnement pour 48h!";
            $_SESSION["login_Type"] = 2;
            break;
        case '1mo':
            addSubscription("P1M");
            $_SESSION["erreurAbo"] = "Merci d'avoir renouvelé votre abonnement de 1 mois!";
            $_SESSION["login_Type"] = 2;
            break;
        case '6mo':
            addSubscription("P6M");
            $_SESSION["erreurAbo"] = "Merci d'avoir renouvelé votre abonnement de 6 mois!";
            $_SESSION["login_Type"] = 2;
            break;
        case 'cancel':
            removeSub();
        $_SESSION["erreurAbo"] = "Vous avez bien annulé votre abonnement !";
        $_SESSION["login_Type"] = 1;
            break;
        default:
            $_SESSION["erreurAbo"] = "Erreur lors de la gestion de l'abonnement";
            break;
    }
    header("Location: ./subscribe.php");
} else {
    header("Location: /home/accueil.php");
}
?>