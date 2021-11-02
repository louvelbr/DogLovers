$(document).ready(function () {
  $('#searchBar').keydown(function(event) { // permet d'utiliser la touche "Entrée" pour autosubmit un formulaire
      if (event.keyCode == 13) {
        this.form.submit();
        return false;
      }
    });
});

function changeVisibility(docID) {
  fields = document.getElementById(docID)
  if (fields.style.visibility == "visible") {
      fields.style.visibility= "hidden";

  } else if (document.getElementById('resultats').innerHTML != '') { // affiche ou non la barre des résultats
      fields.style.visibility = "visible";
  }
}
