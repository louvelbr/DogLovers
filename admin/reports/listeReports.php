<?php
//on démarre une session
session_start();
if ((isset($_SESSION["login_Type"])) && (intval($_SESSION["login_Type"]) == 3)) { ?>
  <!DOCTYPE html>
  <html>

  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Dog Lovers - Liste des Signalements</title>
    <link rel="stylesheet" type="text/css" href="./../../profil/monProfil/MonProfil.css">
    <link rel="shortcut icon" href="./../ressources/favicon.ico" />
  </head>

  <body>
    <div id="blocTitre"></div>
    <div id="Titre">
      <img src="/ressources/dogloverslogo.png" alt="logoDogLovers">
      <h1>Liste des Signalements</h1>
    </div>
    <div class="menu">
      <ul>
        <li><a href="/home/accueil.php">Accueil</a></li>
        <li><a href="/admin/membres.php">Liste des Utilisateurs</a></li>
        <li><a class="active" href="">Liste des Signalements</a></li>
        <li><a href="/admin/conversations.php">Liste des Conversations</a></li>
        <li class="deconnexion"><a href="/login/logout.php">Deconnexion</a></li>
      </ul>
    </div>
    <div id="Infos">
      <div id="autoResize">
          <?php 
          echo "<table>
          <tr>
          <th class='tg-ycr8'>Voir Conversation</th>
          <th class='tg-ycr8'>Supprimer Message</th>
          <th class='tg-ycr8'>Message signalé</th>
          <th class='tg-ycr8'>Raison</th>
          <th class='tg-ycr8'>ID Message</th>
          <th class='tg-ycr8'>ID Conversation</th>
              </tr>\n";
              ?>
<?php
    //on récupère les contenus des fichiers prof et élèves
    $contenu_du_fichierReport = file_get_contents('./../../messagerie/reportList.txt');
    
    //on met chaque ligne dans un tableau
    $nbrElements = explode("\n", $contenu_du_fichierReport);
    $j = 0;
    $i = 0;
    //on démarre une session
    /*on lit le tableau (donc le fichier text ligne par ligne)
jusqu'à ce qu'on ait trouvé un identifiant correspondant
ou jusqu'à la fin du tableau*/
    while ($j < count($nbrElements)-1) {
      /*on met ce qui est entre les § dans des cases d'un tableau afin de pouvoir
  récupérer les différentes données présentes dans chaque ligne*/
      $donnee = explode("§", $nbrElements[$j]);
        ?>
              <?php
                  echo "<tr>
                    ";
                    echo "<td class='tg-wa5c'><a href='./afficheConversationAdmin.php?convID=". $donnee[2] ."'><input type='button' id='bouton2' value='Afficher conversation'></a></td>
                    <td class='tg-wa5c'><a href='/messagerie/supprimerMessage.php?convID=". $donnee[2] ."&msgID=".$donnee[1]. "&adminMode=true". "'><input type='button' id='bouton2' value='Supprimer message'></a></td>
                    <td class='tg-wa5c'>" . $donnee[0] ."</td>
                    <td class='tg-wa5c'>" . $donnee[3] . "</td>
                    <td class='tg-wa5c'>" . $donnee[1] . "</td>
                    <td class='tg-wa5c'>" . $donnee[2] . "</td>
                    </tr> \n\r";
      //on passe à la ligne suivante
      $j++;
    }
    echo "</table>"
?>
        </div>
        </div>
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