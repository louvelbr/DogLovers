<?php
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) > 0)) { ?>
  <!DOCTYPE html>
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Modifier son profil</title>
    <link rel="stylesheet" type="text/css" href="modification.css">
    <script type="text/javascript" src="modification.js"></script>
    <link rel="shortcut icon" href="./../../ressources/favicon.ico" />
  </head>
  <body>

  <?php
    setlocale(LC_ALL,'fr_FR.UTF-8');
    function phpAlert($msg)
    {
      echo '<script type="text/javascript">alert("' . $msg . '")</script>';
    }

    function test_input($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
        /* VERIFICATION FORMULAIRE */
    $nomOk = $prenomOk = $adresseOk = $sexeOk = $situationOk = $tailleOk = $poidsOk = $CouleurCheveuxOk = $CouleurYeuxOk = $pseudoOk = $passwordOk = false;

    $lieuresFilled = $professionFilled = $enfantsFilled = $msgAccFilled = $interetFilled = $citationFilled = $fumeurFilled = $infoschiensFilled = true;

    $erreurNom = $erreurSexe = $erreurPoids = $erreurFumeur = $erreurPrenom = $erreurMsgAcc = $erreurProfession
      = $erreurAdresse = $erreurCitation = $erreurNbDoggos = $erreurPassword = $erreurDoggos = $erreurInterets = $erreurLieuRes
      = $erreurNombreEnf = $erreurSituation = $erreurInfoschiens = $erreurCouleurYeux = $erreurCouleurCheveux
      = $erreurTaille = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["nom"])) {
        $erreurNom = "Le champ nom est requis";
      } else {
        $nomOk = true;
        $_SESSION["nom"] = test_input($_POST["nom"]);
        if (preg_match("/[^a-zA-Z 'éàôêöîùïèç\-]+/", $_SESSION["nom"])) {
          $erreurNom = "Le nom est invalide.";
          $nomOk = false;
        }
      }
      if (empty($_POST["prenom"])) {
        $erreurPrenom = "Le champ prénom est requis";
      } else {
        $prenomOk = true;
        $_SESSION["prenom"] = test_input($_POST["prenom"]);
        if (preg_match("/[^a-zA-Z 'éàôöêîïùèç \-]+/", $_SESSION["prenom"])) {
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

      if (empty($_POST["profession"])) {
        $_SESSION["profession"] = "";
      } else {
        $_SESSION["profession"] = test_input($_POST["profession"]);
        if (preg_match("/[^a-zA-Z \-;'éàôöîêùïè]+/", $_SESSION["profession"])) {
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
        if (preg_match("/[^a-zA-Z ,.\-'!:;?éàêôùöîïèç]+/", $_SESSION["msgAcc"])) {
          $erreurMsgAcc = "Le message d'accueil est invalide.";
          $msgAccFilled = false;
        }
      }

      if (empty($_POST["citation"])) {
        $_SESSION["citation"] = "";
      } else {
        $_SESSION["citation"] = test_input($_POST["citation"]);
        if (preg_match("/[^a-zA-Z ,.\-'!;:?éàôùêöîïèç]/", $_SESSION["citation"])) {
          $erreurCitation = "La citation est invalide.";
          $citationFilled = false;
        }
      }

      if (empty($_POST["interets"])) {
        $_SESSION["interets"] = "";
      } else {
        $_SESSION["interets"] = test_input($_POST["interets"]);
        if (preg_match("/[^a-zA-Z ,.\-!':;?éêàôöùîïèç]+/", $_SESSION["interets"])) {
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
        if (preg_match("/[^a-zA-Z ,.\-';!:?éêàôöîïùèç]+/", $_SESSION["infoschiens"])) {
          $erreurInfoschiens = "Les informations à propos des chiens sont invalides ou contiennent des caractères interdits.";
          $infoschiensFilled = false;
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

    if ($nomOk && $prenomOk && $adresseOk && $sexeOk && $situationOk && $tailleOk && $poidsOk && $CouleurCheveuxOk && $CouleurYeuxOk && $passwordOk && $lieuresFilled && $professionFilled && $enfantsFilled && $msgAccFilled && $interetFilled && $citationFilled && $fumeurFilled && $infoschiensFilled) {
      $_SESSION["dataPassed"] = "true"; // confirmation envoi des données 
      header('Location: ./modificationBis.php'); // redirection et envoi
    }
    ?>
    <div id="part_logo">
      <!--Partie logo-->
      <a href="/login/login.php"><img src="/ressources/logoBis.png" alt="logoBis" class="rounded-corners"></img></a>
    </div>
    <!--Fin partie logo-->

    <div class="textModif">
      Vous pouvez ici modifier vos informations personnelles.
    </div>
    <div id="blocSuppr">
      Si vous souhaitez supprimer votre compte : <a <?php echo "href='./supprimerCompteMembre.php'" ?>><input type='button' id='bouton2' value='Supprimer'></a>
    </div>

    <form accept-charset="UTF-8" action="modification.php" method="POST">
      <div class="page">
        <!--page-->

        <div class="part_gauche">
          <!--float left-->
          <div class="info_générales">
            <!--bloc informations générales-->

            <h3>Informations générales :</h3>
            <label for="nom">Nom</label><br>
            <input name="nom" type="text" pattern="[^§]+" value="<?php echo $_SESSION['nom'] ?>" placeholder="<?php echo $_SESSION['nom'] ?>" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurNom; ?> </span><br>

            <label for="prenom">Prénom</label><br>
            <input name="prenom" pattern="[^§]+" type="text" value="<?php echo $_SESSION['prenom'] ?>" placeholder="<?php echo $_SESSION['prenom'] ?>" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurPrenom; ?> </span><br>

            <label for="adresse">Adresse Mail, cette information sera privée.</label><br>
            <input name="adresse" pattern="[^§]+" type="text" value="<?php echo $_SESSION['adresse'] ?>" placeholder="<?php echo $_SESSION['adresse'] ?>" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser §")' oninput="setCustomValidity('')" required /> <span>* <?php echo $erreurAdresse; ?> </span><br>

            <label for="lieures">Lieu de résidence, cette adresse sera publique.</label><br>
            <input name="lieures" pattern="[^§]+" type="text" value="<?php if(isset($_SESSION['lieuRes'])) echo $_SESSION['lieuRes']; ?>" placeholder="<?php if(isset($_SESSION['lieuRes'])) echo $_SESSION['lieuRes']; ?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurLieuRes; ?> <br>

            <label for="sexe">Sexe</label><br>
            <label><input <?php if(isset($_SESSION['sexe']) && ($_SESSION['sexe'] == "Homme")) echo "checked='checked'"; ?> name="sexe" type="radio" id="Homme" value="Homme" /> Homme </label>
            <label><input <?php if(isset($_SESSION['sexe']) && ($_SESSION['sexe'] == "Femme")) echo "checked='checked'"; ?> name="sexe" type="radio" value="Femme" id="Femme" /> Femme </label> <br>
            <label><input <?php if(isset($_SESSION['sexe']) && ($_SESSION['sexe'] == "Autre")) echo "checked='checked'"; ?> name="sexe" type="radio" value="Autre" id="Autre" /> Autre </label>
            <?php echo $erreurSexe; ?> <br>

            <br><label for="profession">Profession</label> <br>
            <input name="profession" pattern="[^§]+" type="text" value="<?php if(isset($_SESSION['profession'])) echo $_SESSION['profession']; ?>" placeholder="<?php if(isset($_SESSION['lieuRes'])) echo $_SESSION['lieuRes']; ?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurProfession; ?> <br>

            <label for="situation">Situation amoureuse</label> <span>* <?php echo $erreurSituation ?> </span><br>
            <label><input <?php if(isset($_SESSION['situation']) && ($_SESSION['situation'] == "Célibataire")) echo "checked='checked'"; ?> name="situation" type="radio" value="Célibataire" id="Célibataire" required /> Célibataire</label> /
            <label><input <?php if(isset($_SESSION['situation']) && ($_SESSION['situation'] == "Compliqué")) echo "checked='checked'"; ?> name="situation" type="radio" id="Compliqué" value="Compliqué" /> Compliqué</label>
            <label><input id="enfants" name="enfants" type="checkbox" onclick="changeVisibility('nbEnfants')" <?php if (isset($_SESSION['enfants']) && $_SESSION['enfants'] == "on") echo "checked='checked'"; ?> />avec enfants.</label><br>
            <div id="nbEnfants"> <span>* <?php echo $erreurNombreEnf; ?> </span>
              <select name="nombreEnf" id="nombreEnf">
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '-1')) { ?>selected="true" <?php }; ?> value="-1">Ne pas mentionner</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '1')) { ?>selected="true" <?php }; ?> value="1">1 Enfant</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '2')) { ?>selected="true" <?php }; ?> value="2">2 Enfants</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '3-5')) { ?>selected="true" <?php }; ?> value="3-5">Entre 3 et 5 Enfants</option>
                <option <?php if (isset($_SESSION['nombreEnf']) && ($_SESSION['nombreEnf'] == '5+')) { ?>selected="true" <?php }; ?> value="5+">Plus de 5 Enfants</option>
              </select>
            </div>

          </div>
          <!--fin bloc informations générales-->

          <br>

          <div class="info_physiques">
            <!--bloc informations physiques-->
            <h3>Informations physique :</h3>

            <label for="poids">Poids</label><br>
            <label><input type="number" name="poids" id="poids" min="0" value="<?php echo $_SESSION["poids"] ?>" placeholder="<?php echo $_SESSION["poids"] ?>" required>kg</label> <span>* <?php echo $erreurPoids; ?> </span><br>

            <label for="poids">Taille</label><br>
            <label><input type="number" name="taille" id="taille" min="0" value="<?php echo $_SESSION["taille"] ?>" placeholder="<?php echo $_SESSION["taille"] ?>" required>cm</label> <span>* <?php echo $erreurTaille; ?> </span><br>

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
          </div>
          <!--fin bloc informations physiques-->

        </div>
        <!--fin float left-->




        <div class="part_droite">
          <!--float right-->

          <div class="info_profil">
            <!--bloc informations profil-->
            <h3>Informations profil :</h3>

            <label for="msgAcc">Message d'accueil</label><br>
            <input name="msgAcc" pattern="[^§]+" type="text" value="<?php  if(isset($_SESSION['msgAcc'])) echo $_SESSION['msgAcc']; ?>" placeholder="<?php  if(isset($_SESSION['msgAcc'])) echo $_SESSION['msgAcc']; ?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurMsgAcc; ?> <br>

            <label for="citation">Citation</label><br>
            <input name="citation" pattern="[^§]+" type="text" value="<?php if(isset($_SESSION['citation'])) echo $_SESSION['citation'];?>" placeholder="<?php if(isset($_SESSION['citation'])) echo $_SESSION['citation'];?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurCitation; ?> <br>

            <label for="interets">Interets</label><br>
            <input name="interets" pattern="[^§]+" type="text" value="<?php if(isset($_SESSION['interets'])) echo $_SESSION['interets']; ?>" placeholder="<?php if(isset($_SESSION['interets'])) echo $_SESSION['interets']; ?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <?php echo $erreurInterets; ?> <br>
            <label><input type="checkbox" name="fumeur" id="fumeur" <?php if (isset($_SESSION['fumeur']) && ($_SESSION['fumeur'] == "on")) echo "checked='checked'"; ?>>Fumeur ?</label><br>
            <label><input name="chiens" id="chiens" type="checkbox" onclick="changeVisibility('nbChiens')" <?php if (isset($_SESSION['chiens']) && ($_SESSION['chiens'] == "on")) echo "checked='checked'"; ?> /> Je possède un ami à 4 pattes !</label><br>
            <div id="nbChiens"> <span>* <?php echo $erreurNbDoggos; ?> </span>
              <select name="nbDoggos" id="nbDoggos">
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '1')) { ?>selected="true" <?php }; ?> value="1">1 Chien</option>
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '2')) { ?>selected="true" <?php }; ?> value="2">2 Chiens</option>
                <option <?php if (isset($_SESSION['nbDoggos']) && ($_SESSION['nbDoggos'] == '3+')) { ?>selected="true" <?php }; ?> value="3+">3 Chiens ou plus</option>
              </select>
              <input name="infoschiens" pattern="[^§]+" type="text" value="<?php if(isset($_SESSION['infosChiens'])) echo $_SESSION['infosChiens'] ?>" placeholder="<?php if(isset($_SESSION['infosChiens'])) echo $_SESSION['infosChiens'] ?>" oninvalid='setCustomValidity("Merci de ne pas utiliser §")' oninput="setCustomValidity('')" /> <br> <?php echo $erreurInfoschiens; ?> <br>
            </div>
          </div>
          <!--fin bloc informations profil-->

          <br><br>

          <div class="Identifiants">
            <!--bloc identifiants-->
            <h3>Confimer les modifications :</h3>

            <label for="password">Mot de Passe</label><br>
            <input  name="password" type="password" pattern="[^\s]+" value="" placeholder="Mot de passe" oninvalid='setCustomValidity("Champ obligatoire - Merci de ne pas utiliser \"espace\" et § ")' oninput="setCustomValidity('')" required /> <span>* <?php echo '<span>' . $erreurPassword . '</span>'; ?><br>
          </div>
          <!--fin bloc identifiants-->

          <br><br>

          <div class="part_boutons">
            <!--partie boutons-->
            <input type="submit" value="Modifier !"></input>
          </div>
          <!--fin partie boutons-->
          <?php
          if (isset($_SESSION["erreur"])) {
            $_SESSION["dataPassed"] = "false";
            echo '<span id="loginError">'. $_SESSION["erreur"] . '</span>';
            unset($_SESSION["erreur"]);
            }
          ?>
        </div>
        <!--fin float right-->

      </div>
      <!--fin page-->

    </form>
    <script>
      //en rapport avec les box à cocher
       updateCheckBoxOnload('enfants', 'nbEnfants');
       updateCheckBoxOnload('chiens', 'nbChiens');
    </script>
  </body>
 <!-- Footer -->
 <footer id="footer">
      <div class="inner">
        <div class="content">
          <section>
            <h3>Dog Lovers</h3>
            <p>Que vous soyez plutôt Bulldog, Caniche ou Labrador, DogLovers est l'entremetteur des dresseurs. DogLovers est un site de rencontre par affinités, dédié aux célibataires qui recherchent une relation durable et épanouie. L'interaction entre nos célibataires se fait dans un environnement sécurisé. Notre équipe est à votre écoute afin de vous offrir la meilleure expérience possible.</p>
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
          &copy; DogLovers - Tout droits réservés.
        </div>
      </div>
    </footer>
  </html>
<?php
} else {
  header("Location: /home/accueil.php");
}
?>
