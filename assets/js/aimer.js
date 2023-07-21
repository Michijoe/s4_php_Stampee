
let boutonsAime = document.getElementsByName('frmAime');
let eMessageErreurAime = document.getElementById('messageErreurAime');

boutonsAime.forEach(boutonAime => {
    boutonAime.onsubmit = traiterFormulaire;
});


/**
 * Traitement du formulaire suite à l'événement submit
 * @param {Event} event
 */
function traiterFormulaire(event) {
    event.preventDefault();

    // let fd = new FormData(frmAime);

    // fetch('aimer', { method: 'post', body: fd })
    //     .then(reponse => {
    //         if (!reponse.ok) {
    //             throw { message: "Problème technique sur le serveur" };
    //         }
    //         return reponse;
    //     })
    //     .then(mise => {
    //         if (!mise) {
    //             eMessageErreurAime.innerHTML = "Votre like n'est pas correcte";
    //         } else {
    //             // location.reload();
    //         }
    //     })
    //     .catch((e) => {
    //         eMessageErreurAime.innerHTML = "Erreur: " + e.message;
    //     });
}
