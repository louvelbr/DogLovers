function changeVisibility(docID) {
    fields = document.getElementById(docID) 
    if (fields.style.display == "block") {
        fields.style.display = "none";
        if (docID = "nbEnfants") {
        document.getElementById("nombreEnf").options[0].selected = 'selected'; // vérifie si la case n'a pas été coché et la coche si c'est le cas
        }
    } else {
        fields.style.display = "block";
    }
}


function updateCheckBoxOnload(docID,docIDShow) { // met à jour les checkbox au chargement de la page
    fields = document.getElementById(docID);
    toShow = document.getElementById(docIDShow);
    if (fields.checked == true) {
        toShow.style.display = "block";
    }
}