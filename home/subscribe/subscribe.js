function displaySubMode() {
    liste = document.getElementById("listeabonnements"); // affiche ou non la liste des abonnements
    if (liste.style.display == "block") {
        liste.style.display = "none";
    } else {
        liste.style.display = "block";
    }
}