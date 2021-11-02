<?php
session_start();

function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

// permet de changer les images
function changePic(string $photos): string
{
    //triPhoto(); // provisoire qui permettait de trier les images
    $photos = explode("|", $photos); // on isole chaque adresse d'image
    $i = 0;
    while (($i < 4)) { // pour traverser toute la liste d'images
        if (!empty($_SESSION["photo" . $i])) {  // si l'image en cours n'est pas vide
        if (!isset($photos[$i])) { // si dans la liste des photos déjà uploadées, la photo"i" existe
            array_push($photos, $_SESSION["photo" . $i]); // on ajoute la photo à la fin de ce que existe déjà 
        } else { 
            if (!empty($photos[$i])) { // si dans la liste des photos déjà uploadées il supprime l'ancienne image 
                $response = unlink("./../..".$photos[$i]); // suppression image
            }
            $photos[$i] = $_SESSION["photo".$i]; // mise en place de la nouvelle image
        }
    }
    $i++;
    }
    $photos = array_filter($photos);
   $photos = implode("|", $photos);
    return $photos;
}

if (isset($_SESSION["picChange"]) && ($_SESSION["picChange"] == "true")) { // vérification demande de changement d'images
    $path = "./../../register/data/userList.txt"; // chemin fichier utilisateur
    $file = fopen($path, 'r'); // ouverture du fichier
    if ($file) { // si le fichier est bien ouvert alors
        $lastvalue = true;
        while ((($line = fgets($file)) !== false) && $lastvalue) { // on récupère chaque ligne tant que l'on trouve pas l'utilisateur
            $userData = explode("§", $line); // séparation des données de la ligne utilisateur
            //echo "|" . trim($_SESSION["adresseM"]) . "| == |" . trim($userData[sizeof($userData)-2]) . "| <br>";
            if (trim($_SESSION["pseudo"]) == trim($userData[0])) { // si le pseudo ene entrée  correspond a un pseudo en bdd alors 
                $contents = file_get_contents($path);
                $userData[sizeof($userData) - 7] = changePic($userData[sizeof($userData) - 7]); // récupération des données à modifier
                $userData = implode("§", $userData);
                $contents = str_replace($line, $userData, $contents);
                file_put_contents($path, $contents);
                $lastvalue = false;
            }
        }
        fclose($file);
    } else {
        phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
    }
    for ($i=0; $i < 4; $i++) { 
        unset($_SESSION["photo".$i]);
    }
    unset($_SESSION["picChange"]);
    header("Location: ./changePicture.php");
} else {
    header("Location: /errors/erreur403.php");
}
?>