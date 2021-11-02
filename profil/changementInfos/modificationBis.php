<?php
    session_start();
    function phpAlert($msg)
{
  echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

    function remplacementData(array $userData, string $nbrEnfants, $nbrDoggos, $infosChiens):array // récupère toutes les informations mentionnées et les remplace dans la chaine utilisateur
    {
      $userData[1] = $_SESSION["lieuRes"];
      $userData[2] = $_SESSION["sexe"];
      $userData[4] = $_SESSION["profession"];
      $userData[5] = $_SESSION["situation"] . "|" . $nbrEnfants;
      $userData[6] = $_SESSION['poids'] . "|" . $_SESSION['taille'] . "|" . $_SESSION['couleurCheveux'] . "|" . $_SESSION['couleurYeux'];
      $userData[7] = $_SESSION['msgAcc'] ;
      $userData[8] = $_SESSION['citation'];
      $userData[9] = $_SESSION['interets'] ;
      $userData[10] = $_SESSION['fumeur'];
      $userData[11] = $nbrDoggos . "|" . $infosChiens;
      $userData[16] = $_SESSION['nom'] . "|" . $_SESSION['prenom'];
      return $userData;
    }

if (isset($_SESSION["dataPassed"]) && ($_SESSION["dataPassed"] == "true")) { 
    if ($_SESSION['enfants'] == "on") { // affine les données envoyées pour les stocker dans le fichier
      $nbrEnfants = $_SESSION['nombreEnf']; 
      unset($_SESSION['enfants']);
    } else {
      $nbrEnfants = "0";
    }
    if ($_SESSION['chiens'] == "on") {
      unset($_SESSION['chiens']);
      $nbrDoggos = $_SESSION['nbDoggos'];
      $infosChiens = $_SESSION['infoschiens'];
    } else {
      $nbrDoggos = "0";
      $infosChiens = "";
    }
    $lastvalue = true;
    $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
    $file = fopen($path, 'r'); // ouverture du fichier
    if ($file) { // si le fichier est bien ouvert alors
        
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
            $userData = explode("§", $line); // séparation des données de la ligne utilisateur
            if ((password_verify(trim($_SESSION["password"]), trim($userData[sizeof($userData) - 1])) && (trim($_SESSION["pseudo"]) == trim($userData[0])))) {
            //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
                $contents = file_get_contents($path);
                $userData = remplacementData($userData, $nbrEnfants, $nbrDoggos, $infosChiens); // appel de la fonction de remplacement
                $userData = implode("§",$userData);
                $contents = str_replace($line,$userData,$contents); // remplacement des données dans le fichier
                file_put_contents($path, $contents);
                $lastvalue = false;
            }
        }
        fclose($file);
      } else {
        phpAlert("Une erreur s'est produite lors de l'ouverture de la base de données.");
      }
      unset($_SESSION["lieuRes"]);unset($_SESSION["sexe"]);unset($_SESSION["situation"]);unset($_SESSION["poids"]);
        unset($_SESSION["taille"]);unset($_SESSION["couleurCheveux"]);unset($_SESSION["couleurYeux"]);unset($_SESSION["msgAcc"]);
        unset($_SESSION["citation"]);unset($_SESSION["interets"]);unset($_SESSION["fumeur"]);unset($_SESSION["nom"]);unset($_SESSION["prenom"]);
        unset($_SESSION["dataPassed"]);unset($_SESSION["password"]); // destruction des variable temporaires
        if ($lastvalue) {
         $_SESSION["erreur"] = "Mot de passe incorrect.";
        } else {
          $_SESSION["modifie"] = "Données modifiées avec succès.";
        }
        header("Location: /profil/monProfil/MonProfil.php");
    } else {
        header("Location: /errors/erreur403.php");
    }
?>