  // Merci Stack OverFlow https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr 
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }

  function deleteMsg(msgId,convID) {
    if (confirm('Voulez-vous vraiment supprimer ce message ?')) {
      if (msgId.length == 0) {
        return;
      }
      var xmlhttp = new XMLHttpRequest(); // création de la requete
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) { // si la page est prete
          if(alert(this.responseText)){} // écriture du texte d'erreur ou de confirmation dans une alerte
          else window.location.reload();
        }
      }
      xmlhttp.open("GET", "/messagerie/supprimerMessage.php?msgID=" + msgId + "&convID=" + convID, true); // on ouvre la requete
      xmlhttp.send();
    }
  }