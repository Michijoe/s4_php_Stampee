let eUtilisateur = document.getElementById('utilisateur');
let eConnecter = document.getElementById('connecter');
let eCreerCompte = document.getElementById('creerCompte');
let eModaleConnexion = document.getElementById('modaleConnexion');

let eMessageErreurConnexion = document.getElementById('messageErreurConnexion');

eConnecter.onclick = afficherFenetreModale;
frmConnexion.onsubmit = traiterFormulaire;

/**
 * Affichage de la fenêtre modale au clic sur le lien Connecter
 */
function afficherFenetreModale() {
  eMessageErreurConnexion.innerHTML = "&nbsp;";
  document.getElementById('modaleConnexion').showModal();
}

/**
 * Traitement du formulaire dans la fenêtre modale suite à l'événement submit
 * @param {Event} event
 */
function traiterFormulaire(event) {
  event.preventDefault();
  let fd = new FormData(frmConnexion);
  fetch('connecter', { method: 'post', body: fd })
    .then(reponse => {
      if (!reponse.ok) {
        throw { message: "Problème technique sur le serveur" };
      }
      return reponse.json();
    })
    .then(utilisateur => {
      if (!utilisateur) {
        eMessageErreurConnexion.innerHTML = "Courriel ou mot de passe incorrect.";
      } else {
        eConnecter.classList.add('cache');
        eCreerCompte.classList.add('cache');
        eUtilisateur.classList.remove('cache');
        eModaleConnexion.close();
      }
    })
    .catch((e) => {
      eMessageErreurConnexion.innerHTML = "Erreur: " + e.message;
    });
}