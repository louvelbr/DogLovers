<?php
session_start();
if (!(isset($_SESSION["login_Type"]))) { ?>

<!DOCTYPE html>
<html>

  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Inscription</title>
    <link rel="stylesheet" type="text/css" href="./register.css">
    <script type="text/javascript" src="register.js"></script>
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
  </head>

  <body>

  <?php
    setlocale(LC_ALL,'fr_FR.UTF-8'); 
    function phpAlert($msg) // fonction qui permet de débugguer en affichant une alerte JS
    {
      echo '<script type="text/javascript">alert("' . $msg . '")</script>';
    }
    //fonction de test de conformité des données
    function test_input($data) 
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    function testImages($photosFilled,&$erreurPhotos) { 
    if (empty($_FILES["photos"]["name"][0])) { // vérifie si une image à été upload
      $_SESSION["photos"] = "";
    } else {
      $files = array_filter($_FILES['photos']['name']); // retire les images "vides" 
      $total = count($_FILES['photos']['name']); 
      $target_dir = "data/uploads/"; 
      $i = 0;
      while (($i < $total) && ($photosFilled)) { // tant qu'il y a des images dans la queue
        if ($_FILES["photos"]["error"][$i] == 0) {
        $target_file = $target_dir . basename($_FILES["photos"]["tmp_name"][$i]);
        $imageFileType = strtolower(pathinfo(basename($_FILES["photos"]["name"][$i]), PATHINFO_EXTENSION)); // récupération de l'extension de fichier
        $check = getimagesize($_FILES["photos"]["tmp_name"][$i]); 
        if ($check === false) {
          $photosFilled = false; 
          $erreurPhotos = "Le fichier ne semble pas être une image";
        } else
        if (file_exists($target_file)) {
          $photosFilled = false;
          $erreurPhotos = "Un fichier portant le même nom existe déjà...";
        } else
        if ($_FILES["photos"]["size"][$i] > 10000000) { // la taille est determinée dans le fichier php.ini dans tout les cas
          $photosFilled = false;
          $erreurPhotos = "Le fichier est trop volumineux !";
        } else
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
        ) {
          $photosFilled = false;
          $erreurPhotos = "Format non pris en charge, les formats d'images acceptés sont .png, .jpg, .jpeg et .gif !";
        }
        if ($photosFilled) {
          $file_name = $target_dir . uniqid($prefix = "photo_") . "." . $imageFileType;
          if (move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $file_name)) { // écriture du fichier définitif
            $_SESSION["photos"] .= "/register/" . $file_name; // /register ajouté au nom du fichier pour faciliter l'affichage sur les autres pages
            if ($i != ($total - 1)) {
              $_SESSION["photos"] .= "|";
            }
          } else {
            phpAlert("Une erreur s'est produite lors de l'envoi du fichier.");
            $erreurPhotos = "Une erreur s'est produite.";
            $photosFilled = false;
          }
        }
        $i++;
      }  else {
        phpAlert("Image trop grande, merci d'uploader des images dont la taille de dépasse pas". ini_get("upload_max_filesize") ."o chacune.");
        $photosFilled = false;
      }
    }
  }
    return($photosFilled);
  }

  function checkAlreadyRegistered() // vérifie si un utilisateur est déjà enregistré 
{
  $lastvalue = true;
  $file = fopen('./data/userList.txt', 'r');
  if ($file) {
    while ((($line = fgets($file)) !== false) && $lastvalue) {
      $userData = explode("§", $line);
      //echo "|" . trim($_SESSION["adresse"]) . "| == |" . trim($userData[1]) . "|";
      if ((trim($_SESSION["pseudo"]) == trim($userData[0])) || (trim($_SESSION["adresse"]) == trim($userData[sizeof($userData) - 2])) || (trim($_SESSION["pseudo"]) == "destinataires_" )|| ((trim($_SESSION["pseudo"]) == "reportList.txt"))) { // compare les mail/pseudo en BDD pour empecher l'utilisateur de choisir le même que quelqu'un d'autre ou un pseudo interdit
        $lastvalue = false;
        $_SESSION["erreur"] = "login_existant";
      }
    }
    fclose($file);
    return($lastvalue);
  } else {
    phpAlert("Une erreur est survenue lors de l'accès au site...Veuillez réessayer!");
  }
}

/* ****************************  ENSEMBLE DES FONCTIONS DE VERIFICATION DES DONNEES **************************** */

    $nomOk = $prenomOk = $adresseOk = $sexeOk = $dateNaissanceOk = $situationOk = $tailleOk = $poidsOk = $CouleurCheveuxOk = $CouleurYeuxOk = $pseudoOk = $passwordOk = false;

    $lieuresFilled = $professionFilled = $enfantsFilled = $msgAccFilled = $interetFilled = $citationFilled = $fumeurFilled = $infoschiensFilled = $photosFilled = true;

    $erreurNom = $erreurSexe = $erreurPoids = $erreurPseudo = $erreurFumeur = $erreurPrenom = $erreurMsgAcc = $erreurProfession
      = $erreurPhotos = $erreurAdresse = $erreurCitation = $erreurNbDoggos = $erreurPassword = $erreurDoggos = $erreurInterets = $erreurLieuRes
      = $erreurNombreEnf = $erreurSituation = $erreurInfoschiens = $erreurCouleurYeux = $erreurCouleurCheveux
      = $erreurDateNaissance = $erreurTaille = "";

    $_SESSION["nom"] = $_SESSION["sexe"] = $_SESSION["poids"] = $_SESSION["pseudo"] = $_SESSION["fumeur"] = $_SESSION["prenom"] = $_SESSION["msgAcc"]
      = $_SESSION["photos"] = $_SESSION["adresse"] = $_SESSION["citation"] = $_SESSION["nbDoggos"] = $_SESSION["password"] = $_SESSION["interets"]
      = $_SESSION["nombreEnf"] = $_SESSION["situation"] = $_SESSION["infoschiens"] = $_SESSION["couleurYeux"] = $_SESSION["couleurCheveux"]
      = $_SESSION["dateNaissance"] = $_SESSION["taille"] = $_SESSION["lieures"] = $_SESSION["profession"] = $_SESSION["fumeur"] = $_SESSION["chiens"] = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["nom"])) {
        $erreurNom = "Le champ nom est requis";
      } else {
        $nomOk = true;
        $_SESSION["nom"] = test_input($_POST["nom"]);
        if (preg_match("/[^a-zA-Z 'éàôöùêîïèç\-]+/", $_SESSION["nom"])) {
          $erreurNom = "Le nom est invalide.";
          $nomOk = false;
        }
      }
      if (empty($_POST["prenom"])) {
        $erreurPrenom = "Le champ prénom est requis";
      } else {
        $prenomOk = true;
        $_SESSION["prenom"] = test_input($_POST["prenom"]);
        if (preg_match("/[^a-zA-Z 'éàôöîùêïèç\-]+/", $_SESSION["prenom"])) {
          $erreurPrenom = "Le prénom est invalide.";
          $prenomOk = false;
        }
      }
      if (empty($_POST["adresse"])) {
        $erreurAdresse = "Le champ adresse est requis";
      } else {
        $adresseOk = true;
        $_SESSION["adresse"] = test_input($_POST["adresse"]);
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $_SESSION["adresse"])) {
          $erreurAdresse = "<br><div class=\"mail_pasok\">L'adresse mail est invalide.</div>";
          $adresseOk = false;
        }
      }
      if (empty($_POST["lieures"])) {
        $lieures = "";
      } else {
        $_SESSION["lieures"] = test_input($_POST["lieures"]);
        if (preg_match("/[§]+/", $_SESSION["lieures"])) {
          $erreurLieuRes = "Le lieu de résidence contient des caractères invalides.";
          $lieuResFilled = false;
        }
      }

      if (empty($_POST["sexe"])) {
        $erreurSexe = "Le champ sexe est requis";
      } else {
        $sexeOk = true;
        $_SESSION["sexe"] = test_input($_POST["sexe"]);
        if (($_SESSION["sexe"] != "Homme") && ($_SESSION["sexe"] != "Femme") && ($_SESSION["sexe"] != "Autre")) {
          $erreurSexe = "Le sexe est invalide.";
          $sexeOk = false;
        }
      }

      if (empty($_POST["dateNaissance"])) {
        $erreurDateNaissance = "Le champ date de naissance est requis";
      } else {
        $dateNaissanceOk = true;
        $_SESSION["dateNaissance"] = test_input($_POST["dateNaissance"]);
        if (!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $_SESSION["dateNaissance"])) {
          $erreurDateNaissance = "La date de naissance est invalide.";
          $dateNaissanceOk = false;
        }
      }

      if (empty($_POST["profession"])) {
        $_SESSION["profession"] = "";
      } else {
        $_SESSION["profession"] = test_input($_POST["profession"]);
        if (preg_match("/[^a-zA-Z '\-;éàôêöîïùèç]+/", $_SESSION["profession"])) {
          $erreurProfession = "La profession est invalide.";
          $professionFilled = false;
        }
      }

      if (empty($_POST["situation"])) {
        $erreurSituation = "Le champ situation amoureuse est requis";
      } else {
        $situationOk = "true";
        $_SESSION["situation"] = test_input($_POST["situation"]);
        if (($_SESSION["situation"] != "Célibataire") && ($_SESSION["situation"] != "Compliqué")) {
          $erreurSituation = "La situation amoureuse est invalide.";
          $situationOk = false;
        }
      }

      if (!isset($_POST["enfants"])) {
        $_SESSION["enfants"] = "";
      } else {
        $_SESSION["enfants"] = test_input($_POST["enfants"]);
        if ($_SESSION["enfants"] != "on") {
          $erreurEnfants = "Une erreur s'est produite";
          $enfantsFilled = false;
        } else {
          if (empty($_POST["nombreEnf"])) {
            $erreurNombreEnf = "Le champ nombre d'enfants est requis";
            $enfantsFilled = false;
          } else {
            $_SESSION["nombreEnf"] = test_input($_POST["nombreEnf"]);
            if (($_SESSION["nombreEnf"] != "1") && ($_SESSION["nombreEnf"] != "2") && ($_SESSION["nombreEnf"] != "3-5") && ($_SESSION["nombreEnf"] != "5+") && ($_SESSION["nombreEnf"] != "-1")) {
              $erreurNombreEnf = "Le nombre d'enfants est invalide.";
              $enfantsFilled = false;
            }
          }
        }
      }

      if (empty($_POST["poids"])) {
        $erreurPoids = "Le champ poids est requis";
      } else {
        $poidsOk = true;
        $_SESSION["poids"] = test_input($_POST["poids"]);
        if (($_SESSION["poids"] < 0) || ($_SESSION["poids"] > 600)) {
          $erreurPoids = "Le poids est invalide.";
          $poidsOk = false;
        }
      }

      if (empty($_POST["taille"])) {
        $erreurTaille = "Le champ poids est requis";
      } else {
        $_SESSION["taille"] = test_input($_POST["taille"]);
        $tailleOk = true;
        if (($_SESSION["taille"] < 0) || ($_SESSION["taille"] > 260)) {
          $erreurTaille = "La taille est invalide.";
          $tailleOk = false;
        }
      }

      if (empty($_POST["couleurCheveux"])) {
        $erreurCouleurCheveux = "Le champ couleur de cheveux est requis";
      } else {
        $CouleurCheveuxOk = "true";
        $_SESSION["couleurCheveux"] = test_input($_POST["couleurCheveux"]);
        if (($_SESSION["couleurCheveux"] != "Noir") && ($_SESSION["couleurCheveux"] != "Brun") && ($_SESSION["couleurCheveux"] != "Auburn")
          && ($_SESSION["couleurCheveux"] != "Châtain") && ($_SESSION["couleurCheveux"] != "Roux") && ($_SESSION["couleurCheveux"] != "Blond Vénitien")
          && ($_SESSION["couleurCheveux"] != "Blond") && ($_SESSION["couleurCheveux"] != "Blanc") && ($_SESSION["couleurCheveux"] != "Autre")
        ) {
          $erreurCouleurCheveux = "La couleur de cheveux est invalide.";
          $CouleurCheveuxOk = false;
        }
      }

      if (empty($_POST["couleurYeux"])) {
        $erreurCouleurYeux = "Le champ couleur de yeux est requis";
      } else {
        $CouleurYeuxOk = true;
        $_SESSION["couleurYeux"] = test_input($_POST["couleurYeux"]);
        if (($_SESSION["couleurYeux"] != "Bleu") && ($_SESSION["couleurYeux"] != "Vert") && ($_SESSION["couleurYeux"] != "Marron")
          && ($_SESSION["couleurYeux"] != "Noisette") && ($_SESSION["couleurYeux"] != "Noir") && ($_SESSION["couleurYeux"] != "Autre")
        ) {
          $erreurCouleurYeux = "La couleur des yeux est invalide.";
          $CouleurYeuxOk = false;
        }
      }

      if (empty($_POST["msgAcc"])) {
        $_SESSION["msgAcc"] = "";
      } else {
        $_SESSION["msgAcc"] = test_input($_POST["msgAcc"]);
        if (preg_match("/[^a-zA-Z ,.'\-!:;?éàôöîïêùèç&#]+/", $_SESSION["msgAcc"])) {
          $erreurMsgAcc = "Le message d'accueil est invalide.";
          $msgAccFilled = false;
        }
      }

      if (empty($_POST["citation"])) {
        $_SESSION["citation"] = "";
      } else {
        $_SESSION["citation"] = test_input($_POST["citation"]);
        if (preg_match("/[^a-zA-Z1-9 ,.'\-!;:?éàôöîïùèêç&#]/", $_SESSION["citation"])) {
          $erreurCitation = "La citation est invalide.";
          $citationFilled = false;
        }
      }

      if (empty($_POST["interets"])) {
        $_SESSION["interets"] = "";
      } else {
        $_SESSION["interets"] = test_input($_POST["interets"]);
        if (preg_match("/[^a-zA-Z ,.'\-!:;?éàôöîïèêùç&#]+/", $_SESSION["interets"])) {
          $erreurInterets = "Les centres d'interets sont invalides.";
          $interetFilled = false;
        }
      }

      if (empty($_POST["fumeur"])) {
        $_SESSION["fumeur"] = "";
      } else {
        $_SESSION["fumeur"] = test_input($_POST["fumeur"]);
        if ($_SESSION["fumeur"] != "on") {
          $erreurFumeur = "Une erreur s'est produite.";
          $fumeurFilled = false;
        }
      }

      if (empty($_POST["chiens"])) {
        $_SESSION["chiens"] = "";
      } else {
        $_SESSION["chiens"] = test_input($_POST["chiens"]);
        if ($_SESSION["chiens"] != "on") {
          $erreurDoggos = "Une erreur s'est produite";
          $chiensFilled = false;
        } else {
          if (empty($_POST["nbDoggos"])) {
            $erreurNbDoggos = "Le champ nombre de chiens est requis";
            $chiensFilled = false;
          } else {
            $_SESSION["nbDoggos"] = test_input($_POST["nbDoggos"]);
            if (($_SESSION["nbDoggos"] != "1") && ($_SESSION["nbDoggos"]  != "2") && ($_SESSION["nbDoggos"]  != "3+")) {
              $erreurNbDoggos = "Le nombre de chiens est invalide.";
              $chiensFilled = false;
            }
          }
        }
      }
      if (empty($_POST["infoschiens"])) {
        $_SESSION["infoschiens"]  = "";
      } else {
        $_SESSION["infoschiens"] = test_input($_POST["infoschiens"]);
        if (preg_match("/[^a-zA-Z ,.'\-!:;?éàôöùîïèêç&#]+/", $_SESSION["infoschiens"])) {
          $erreurInfoschiens = "Les informations à propos des chiens sont invalides ou contiennent des caractères interdits.";
          $infoschiensFilled = false;
        }
      }

      if (empty($_POST["pseudo"])) {
        $erreurPseudo = "Le champ pseudo est requis";
      } else {
        $pseudoOk = "true";
        $_SESSION["pseudo"] = test_input($_POST["pseudo"]);
        if (preg_match("/[^a-zA-Z '\-éàôöîïêèùç&#]+/", $_SESSION["pseudo"])) {
          $erreurPseudo = "Le pseudo est invalide.";
          $pseudoOk = false;
        }
      }

      if (empty($_POST["password"])) {
        $erreurPassword = "Le champ mot de passe est requis";
      } else {
        $passwordOk = "true";
        $_SESSION["password"] = test_input($_POST["password"]);
        if (!preg_match("/[^§\s]+/", $_SESSION["password"])) {
          $erreurPassword = "Le mot de passe est invalide.";
          $passwordOk = false;
        }
      }
    }
            /* **************************** TEST DE REDIRECTION **************************** */   
    if ($nomOk && $prenomOk && $adresseOk && $sexeOk && $dateNaissanceOk && $situationOk && $tailleOk && $poidsOk && $CouleurCheveuxOk && $CouleurYeuxOk && $pseudoOk && $passwordOk && $lieuresFilled && $professionFilled && $enfantsFilled && $msgAccFilled && $interetFilled && $citationFilled && $fumeurFilled && $infoschiensFilled && (checkAlreadyRegistered())) {
      if (testImages($photosFilled,$erreurPhotos)) { // si toutes les données sont bien remplies (et légales) et que les images le sont aussi 
      $_SESSION["dataPassed"] = "true"; 
      header('Location: ./confirmRegistration.php'); // on inscrit 
      }
    }
    ?>

    <div id="part_logo"> <!--Partie logo-->
      <a href="./../login/login.php"><img src="./../ressources/logoBis.png" alt="logoBis" class="rounded-corners"></img></a>
    </div> <!--Fin partie logo-->

    <form accept-charset="UTF-8" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
      <div class="page"><!--page-->

        <div class="part_gauche"><!--float left-->
          <div class="info_générales"><!--bloc informations générales-->

            <h3>Informations générales :</h3>

            <label for="nom">Nom</label><br>
            <input name="nom" type="text" pattern="[^§]+" value="<?php echo $_SESSION['nom'] ?>" placeholder="Votre nom" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurNom; ?> </span><br>

            <label for="prenom">Prénom</label><br>
            <input name="prenom" pattern="[^§]+" type="text" value="<?php echo $_SESSION['prenom'] ?>" placeholder="Votre prénom" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurPrenom; ?> </span><br>

            <label for="adresse">Adresse Mail, cette information sera privée.</label><br>
            <input name="adresse" pattern="[^§]+" type="text" value="<?php echo $_SESSION['adresse'] ?>" placeholder="Adresse Mail" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurAdresse; ?> </span><br>

            <label for="lieures">Lieu de résidence, cette adresse sera publique.</label><br>
            <input name="lieures" pattern="[^§]+" type="text" value="<?php echo $_SESSION['lieures'] ?>" placeholder="(Pays, Ville, Département)" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurLieuRes; ?> <br>

            <label for="sexe">Sexe</label><br>
            <label><input checked="checked" name="sexe" type="radio" id="Homme" value="Homme" /> Homme </label>
            <label><input name="sexe" type="radio" value="Femme" id="Femme" /> Femme </label> <br>
            <label><input name="sexe" type="radio" value="Autre" id="Autre" /> Autre </label>
            <?php echo $erreurSexe; ?> <br>

            <br><label for="birthday">Date de Naissance</label><br>
            <input type="date" name="dateNaissance" value="<?php echo $_SESSION['dateNaissance'] ?>" required> <span>* <?php echo $erreurDateNaissance; ?> </span><br>

            <br><label for="profession">Profession</label> <br>
            <input name="profession" pattern="[^§]+" type="text" value="<?php echo $_SESSION['profession'] ?>" placeholder="Votre profession ou activité." oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurProfession; ?> <br>

            <label for="situation">Situation amoureuse</label> <span>* <?php echo $erreurSituation ?> </span><br>
            <label><input checked="checked" name="situation" type="radio" value="Célibataire" id="Célibataire" required /> Célibataire</label> /
            <label><input name="situation" type="radio" id="Compliqué" value="Compliqué" /> Compliqué</label>
            <label><input id="enfants" name="enfants" type="checkbox" onclick="changeVisibility('nbEnfants')" <?php if (isset($_POST['enfants'])) echo "checked='checked'"; ?> />avec enfants.</label><br>
            <div id="nbEnfants"> <span>* <?php echo $erreurNombreEnf; ?> </span>
              <select name="nombreEnf" id="nombreEnf">
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '-1')) { ?>selected="true" <?php }; ?> value="-1">Ne pas mentionner</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '1')) { ?>selected="true" <?php }; ?> value="1">1 Enfant</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '2')) { ?>selected="true" <?php }; ?> value="2">2 Enfants</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '3-5')) { ?>selected="true" <?php }; ?> value="3-5">Entre 3 et 5 Enfants</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '5+')) { ?>selected="true" <?php }; ?> value="5+">Plus de 5 Enfants</option>
              </select>
            </div>

          </div><!--fin bloc informations générales-->

          <br>

          <div class="info_physiques"><!--bloc informations physiques-->
            <h3>Informations physique :</h3>

            <label for="poids">Poids</label><br>
            <label><input type="number" name="poids" id="poids" min="0" value="<?php echo $_SESSION["poids"] ?>" required>kg</label> <span>* <?php echo $erreurPoids; ?> </span><br>

            <label for="poids">Taille</label><br>
            <label><input type="number" name="taille" id="taille" min="0" value="<?php echo $_SESSION["taille"] ?>" required>cm</label> <span>* <?php echo $erreurTaille; ?> </span><br>

            <label for="couleurCheveux">Couleur de vos cheveux</label> <span>* <?php echo $erreurCouleurCheveux; ?> </span> <br>
            <select name="couleurCheveux" id="couleurCheveux">
              <option <?php if (isset($_SESSION['couleurCheveux']) && ($_SESSION['couleurCheveux'] == 'Noir')) { ?>selected="true" <?php }; ?> value="Noir">Noir</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Brun')) { ?>selected="true" <?php }; ?> value="Brun">Brun</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Auburn')) { ?> selected="true" <?php }; ?> value="Auburn">Auburn</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Châtain')) { ?>selected="true" <?php }; ?> value="Châtain">Châtain</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Roux')) { ?>selected="true" <?php }; ?> value="Roux">Roux</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Blond Vénitien')) { ?>selected="true" <?php }; ?> value="Blond Vénitien">Blond Vénitien</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Blond')) { ?>selected="true" <?php }; ?> value="Blond">Blond</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Blanc')) { ?>selected="true" <?php }; ?> value="Blanc">Blanc</option>
              <option <?php if (isset($_SESSION['couleurCheveux']) &&  ($_SESSION['couleurCheveux'] == 'Autre')) { ?>selected="true" <?php }; ?> value="Autre">Autre</option>
            </select> <br>

            <label for="couleurYeux">Couleur de vos yeux</label> <span>* <?php echo $erreurCouleurYeux; ?> </span><br>
            <select name="couleurYeux" id="couleurYeux">
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Bleu')) { ?>selected="true" <?php }; ?> value="Bleu">Bleu</option>
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Vert')) { ?>selected="true" <?php }; ?> value="Vert">Vert</option>
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Marron')) { ?>selected="true" <?php }; ?> value="Marron">Marron</option>
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Noisette')) { ?>selected="true" <?php }; ?> value="Noisette">Noisette</option>
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Noir')) { ?>selected="true" <?php }; ?> value="Noir">Noir</option>
              <option <?php if (isset($_SESSION['couleurYeux']) && ($_SESSION['couleurYeux'] == 'Autre')) { ?>selected="true" <?php }; ?> value="Autre">Autre</option>
            </select>
          </div><!--fin bloc informations physiques-->

        </div><!--fin float left-->




        <div class="part_droite"><!--float right-->

          <div class="info_profil"><!--bloc informations profil-->
            <h3>Informations profil :</h3>

            <label for="msgAcc">Message d'accueil</label><br>
            <input name="msgAcc" pattern="[^§]+" type="text" value="<?php echo $_SESSION['msgAcc'] ?>" placeholder="Un petit message d'accueil" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurMsgAcc; ?> <br>

            <label for="citation">Citation</label><br>
            <input name="citation" pattern="[^§]+" type="text" value="<?php echo $_SESSION['citation'] ?>" placeholder="Une citation ?" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurCitation; ?> <br>

            <label for="interets">Interets</label><br>
            <input name="interets" pattern="[^§]+" type="text" value="<?php echo $_SESSION['interets'] ?>" placeholder="Vos interets" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurInterets; ?> <br>
            <label><input type="checkbox" name="fumeur" id="fumeur" <?php if (isset($_POST['fumeur'])) echo "checked='checked'"; ?>>Fumeur ?</label><br>
            <label><input name="chiens" id="chiens" type="checkbox" onclick="changeVisibility('nbChiens')" <?php if (isset($_POST['chiens'])) echo "checked='checked'"; ?> /> Je possède un ami à 4 pattes !</label><br>
            <div id="nbChiens"> <span>* <?php echo $erreurNbDoggos; ?> </span>
              <select name="nbDoggos" id="nbDoggos">
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '1')) { ?>selected="true" <?php }; ?> value="1">1 Chien</option>
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '2')) { ?>selected="true" <?php }; ?> value="2">2 Chiens</option>
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '3+')) { ?>selected="true" <?php }; ?> value="3+">3 Chiens ou plus</option>
              </select>
              <input name="infoschiens" pattern="[^§]+" type="text" value="<?php echo $_SESSION['infoschiens'] ?>" placeholder="Infos chiens" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <br> <?php echo $erreurInfoschiens; ?> <br>
            </div>
          </div><!--fin bloc informations profil-->

          <br><br>

          <div class="photo"><!--bloc photo-->
            <h3>Photos !</h3>
            <span>Vous pouvez mettre en ligne jusqu'à 4 photos ! /!\ La taille d'un fichier ne peut dépasser <?php echo ini_get("upload_max_filesize"); ?>o ! </span> <br>
            <input type="file" id="photos" name="photos[]" accept="image/png, image/jpeg, image/jpg, image/gif" multiple> <br>
            <?php echo $erreurPhotos; ?> </span>
          </div><!--fin bloc photo-->

          <br><br>

          <div class="Identifiants"><!--bloc identifiants-->
            <h3>Identifiants :</h3>

            <label for="pseudo">Pseudo</label><br>
            <input name="pseudo" type="text" pattern="[^\s§]+" value="<?php echo $_SESSION['pseudo'] ?>" placeholder="Votre pseudo" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et § ")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurPseudo; ?> </span><br>

            <label for="password">Mot de Passe</label><br>
            <input name="password" type="password" pattern="[^\s]+" value="" placeholder="Mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et § ")' oninput="setCustomValidity('')" required /> <span>* <?php echo '<span>' . $erreurPassword . '</span>'; ?><br>
          </div><!--fin bloc identifiants-->

          <br><br>


            <?php
          if (isset($_SESSION["erreur"]) && ($_SESSION["erreur"] == "login_existant")) {
            $_SESSION["dataPassed"] = "false";
            echo '<div class="deja_use">Utilisateur déjà enregistré<br>( Mail ou Pseudo déjà utilisé...)</div>';
            unset($_SESSION["erreur"]);
          }
          ?>

          <div class="part_boutons"><!--partie boutons-->
            <input type="submit" value="Ajouter !"></input>
          </div><!--fin partie boutons-->

        </div><!--fin float right-->

      </div><!--fin page-->

  </form>

    <script>//en rapport avec les box à cocher
      updateCheckBoxOnload('enfants', 'nbEnfants');
      updateCheckBoxOnload('chiens', 'nbChiens');
    </script>

  </body>
 <!-- Footer -->
 <footer id="footer">
      <div class="inner">
        <div class="content">
          <section>
            <h3>Dog Lover</h3>
            <p>Que vous soyez plutôt Bulldog, Caniche ou Labrador, DogLover est l'entremetteur des dresseurs. DogLover est un site de rencontre par affinités, dédié aux célibataires qui recherchent une relation durable et épanouie. L'interaction entre nos célibataires se fait dans un environnement sécurisé. Notre équipe est à votre écoute afin de vous offrir la meilleure expérience possible.</p>
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
          &copy; DogLover - Tout droits réservés.
        </div>
      </div>
    </footer>
</html>
<?php
} else {
  header('Location: ./../home/accueil.php');
} ?>
