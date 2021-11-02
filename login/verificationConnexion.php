<?php
session_start();
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["pseudo"]) && isset($_POST["password"]))) {
$_SESSION['pseudo'] = $_POST['pseudo']; // sauvegarde du pseudo utilisateur
$path = "./../register/data/userList.txt"; // chemin du fichier des utilisateurs

function phpAlert(string $msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

function changeMemberShip($user)
{
    $contents = file_get_contents($GLOBALS["path"]); //récupération contenu fichier utilisateur
    $userTmp = $user; // duplication ligne de l'utilisateur concerné
    $userTmp[sizeof($userTmp) - 6] = "free"; // modification du type d'abonnement
    $userTmp = implode("§", $userTmp); // rassemblement ligne utilisateur
    $contents = str_replace(implode("§", $user), $userTmp, $contents); // remplacement ligne dans le contenu total
    file_put_contents($GLOBALS["path"], $contents); // réinjection du contenu dans le fichier
}

function getMemberShipType(array $memberString)
{
    $memberShipString = $memberString[sizeof($memberString) - 6]; // récupération de la case "membership" qui contient les informations sur le "type" d'utilisateur
    $infoMS = explode(":", $memberShipString);  // séparation type de membre et date expiration de l'abonnement
    if ($infoMS[0] == "free") { // test si abonnement gratuit
        return "1"; // mode 1
    } else if ($infoMS[0] == "member") { // test si membre
        if (date("Y-m-d") > $infoMS[1]) { // comparaison date actuelle et date expiration
            changeMemberShip($memberString); // mise à jour de l'abonnement (expiré)
            $_SESSION["memberShipExpired"] = "true"; // abonnement expiré
            return "1"; // mode 1
        } else {
            return "2"; // mode 2
        }
    } else if ($infoMS[0] == "admin") {
        return "3"; // mode admin
    } else if ($infoMS[0] == "banned"){
      $_SESSION["banned"] = "true";
        return "0";
    }
}

$file = fopen($path, 'r'); // ouverture du fichier
if ($file) {
    while (($line = fgets($file)) !== false) { // récupération de la ligne
        $userData = explode("§", $line); // séparation de la ligne
        if ((password_verify(trim($_POST["password"]), trim($userData[sizeof($userData) - 1])) && (trim($_POST["pseudo"]) == trim($userData[0])))) { // vérification ID/PW pour chaque ligne
            // $_SESSION["udata"] = array();
            // $_SESSION["udata"] = array_slice($userData,0,sizeof($userData)-2);
            $_SESSION["login_Type"] = getMemberShipType($userData); // récupération type utilisateur
            if ($_SESSION["banned"] == "true" ){
              header('Location: /login/login.php');
            }else{
            header('Location: ./../home/accueil.php'); // redirection
          }
            fclose($file); // fermeture fichier
            exit(); // arrêt fonction
        }
    }
    fclose($file);
}else{
    phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
}
// $file = fopen('./../register/data/adminList.txt', 'r'); // ouverture du fichier pour le administrateurs
// if ($file) {
//     while (($line = fgets($file)) !== false) {
//         $userData = explode("§", $line);
//         if ((password_verify(trim($_POST["password"]),trim($userData[sizeof($userData)-1])) && (trim($_POST["pseudo"]) == trim($userData[0])))) {
//             $_SESSION["udata"] = array();
//             $_SESSION["udata"] = array_slice($userData,0,sizeof($userData)-2);
//             $_SESSION["login_Type"] = "3";
//             header('Location: ./../home/accueil.php');
//             fclose($file);
//             exit();
//         }
//     }
//     fclose($file);
// } else {
//     phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
// }
$_SESSION["error"] = "error"; // mise en erreur
header('Location: /login/login.php');  // redirection vers l'accueil
} else 
header('Location: /login/login.php');  // redirection vers l'accueil?>
