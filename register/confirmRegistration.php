<!-- Enregistrement des inscirts -->
<?php
session_start();
function phpAlert($msg)
{
  echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

if (isset($_SESSION["dataPassed"]) && ($_SESSION["dataPassed"] == "true")) { 
    if ($_SESSION['enfants'] == "on") { // affinage des données données complméntaires
      $nbrEnfants = $_SESSION['nombreEnf'];
    } else {
      $nbrEnfants = "0";
    }
    if ($_SESSION['chiens'] == "on") { // affinage des données données complméntaires
      $nbrDoggos = $_SESSION['nbDoggos'];
      $infosChiens = $_SESSION['infoschiens'];
    } else {
      $nbrDoggos = "0";
      $infosChiens = "";
    }
    //on récupère les données de l'inscrit dans une variable
    $content =  $_SESSION['pseudo']
      . "§" . $_SESSION['lieures']
      . "§" . $_SESSION['sexe'] . "§" . $_SESSION['dateNaissance'] . "§" . $_SESSION['profession']
      . "§" . $_SESSION['situation'] . "|" . $nbrEnfants
      . "§" . $_SESSION['poids'] . "|" . $_SESSION['taille'] . "|" . $_SESSION['couleurCheveux'] . "|" . $_SESSION['couleurYeux']
      . "§" . $_SESSION['msgAcc'] . "§" . $_SESSION['citation'] . "§" . $_SESSION['interets'] . "§" . $_SESSION['fumeur']
      . "§" . $nbrDoggos . "|" . $infosChiens
      . "§" . $_SESSION['photos']
      . "§" . "free" // [sizeof(userData)-6]
      . "§" . date("Y-m-d") 
      . "§" . uniqid($prefix = "user_")
      . "§" . $_SESSION['nom'] . "|" . $_SESSION['prenom'] 
      . "§" . $_SESSION['adresse'] 
      . "§" . password_hash($_SESSION['password'], PASSWORD_DEFAULT) . "\n";
    //on écrit ce que contient la variable dans le fichier nommé userList.txt
    //FILE_APPEND permet d'écrire à la suite du fichier
    $contentBis = $_SESSION["pseudo"]."\n";
    file_put_contents('./data/matchs.txt', $contentBis, FILE_APPEND);
    file_put_contents('./data/userList.txt', $content, FILE_APPEND);
    // remove all session variables
    session_unset();
    session_destroy();
    session_start();
    $_SESSION["inscrit"] = "success";
    header('location: /login/login.php'); 
  } else {
  header('Location: /errors/erreur403.php'); 
}
?>