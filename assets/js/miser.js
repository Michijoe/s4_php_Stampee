
let eMessageErreurMise = document.getElementById('messageErreurMise');

frmMise.onsubmit = traiterFormulaire;

/**
 * Traitement du formulaire suite à l'événement submit
 * @param {Event} event
 */
function traiterFormulaire(event) {
    event.preventDefault();

    let fd = new FormData(frmMise);


    fetch('miser', { method: 'post', body: fd })
        .then(reponse => {
            if (!reponse.ok) {
                throw { message: "Problème technique sur le serveur" };
            }
            return reponse;
        })
        .then(mise => {
            if (!mise) {
                eMessageErreurMise.innerHTML = "Votre mise n'est pas correcte";
            } else {
                location.reload();
            }
        })
        .catch((e) => {
            eMessageErreurMise.innerHTML = "Erreur: " + e.message;
        });
}