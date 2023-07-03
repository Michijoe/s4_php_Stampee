document.querySelectorAll('[data-type]').forEach(e => e.onclick = afficherModaleJonction);

let eH1                    = document.querySelector('#modaleJonction h1');
let eMessageErreurJonction = document.getElementById("messageErreurJonction");
let eJonctionDisponibles   = document.getElementById("jonctionDisponibles");
let eJonctionPris          = document.getElementById("jonctionPris");
appliquerLeDeposer(eJonctionDisponibles);
appliquerLeDeposer(eJonctionPris);

let entites = {
  pays:        { lib: 'Pays',         champs: ['pays_nom']                                                       },
  realisateur: { lib: 'Réalisateurs', champs: ['realisateur_nom', 'realisateur_prenom']                          },
  acteur:      { lib: 'Acteurs',      champs: ['acteur_nom', 'acteur_prenom'],          priorite: 'f_a_priorite' }
};
let divEntite;
let entite;
let film_id;
let film_titre;
let priorite;
let itemsPris;

/**
 * Affichage d'une fenêtre modale pour gérer une table de jonction
 */
function afficherModaleJonction() {
  eMessageErreurJonction.innerHTML = "&nbsp;";
  divEntite = this;
  entite     = this.dataset.type;
  film_id    = this.parentNode.dataset.id;
  film_titre = this.parentNode.dataset.titre;
  fetch('admin?entite='+entite+'&action=lf&film_id='+film_id)
  .then (reponse => {
    if (!reponse.ok) throw { message:  "Problème technique sur le serveur" };
    return reponse.json();
  })
  .then (liste => {
    let lib      = entites[entite].lib;
    eH1.innerHTML                  = lib + ' du film &nbsp;' + film_titre;
    eJonctionDisponibles.innerHTML = '<p>' + lib + ' disponibles</p>';
    eJonctionPris.innerHTML        = '<p>' + lib + ' de ce film</p>';
    priorite = entites[entite].priorite ?? null;
    itemsPris = []; 
    liste.forEach(item => {
      let nom = entites[entite].champs.reduce(
        (nom, champ) => nom + item[champ] + ' ',
        ''
      );
      if (item.film_id === null) {
        eJonctionDisponibles.insertAdjacentHTML('beforeend', '<span data-idj=' + item[entite+'_id'] + '>'+nom+'</span>');
      } else {
        itemsPris.push(item[entite + '_id']);
        let spanHTML = '<span data-idj=' + item[entite + '_id'] + '>' + ( priorite ? item[priorite] + ' - ' : '') + nom + '</span>'
        eJonctionPris.insertAdjacentHTML('beforeend', spanHTML);
      }
    });
    let listeSpans = document.querySelectorAll('#jonction span');
    listeSpans.forEach(e => appliquerLeGlisser(e));
    document.getElementById('modaleJonction').showModal();
  })
  .catch((e) => {
    console.log("Erreur: " + e.message);
  });
}

/**
 * Gestion du glisser sur un élément span
 * @param {Element} span 'glissable'
 */
function appliquerLeGlisser(eGlissable) {
  eGlissable.draggable = true;
  eGlissable.addEventListener("dragstart", (evt) => {
    let idj  = evt.target.dataset.idj;
    let statut = evt.target.parentNode.id;
    let contexte = JSON.stringify({'idj' : idj, 'statut' : statut});
    evt.dataTransfer.setData("text", contexte);
  });
  eGlissable.addEventListener("drag",    evt => evt.target.style.opacity = 0);
  eGlissable.addEventListener("dragend", evt => evt.target.style.opacity = 1);
}

/**
 * Gestion du déposer d'un élément span
 * @param {Element} div 'déposable'
 */
function appliquerLeDeposer(eDeposable) {
  eDeposable.addEventListener("dragover", (evt) => evt.preventDefault());
  eDeposable.addEventListener("drop",     (evt) => {
    evt.preventDefault();
    let contexte = JSON.parse(evt.dataTransfer.getData("text"));
    eMessageErreurJonction.innerHTML = "&nbsp;";

    // =========================================================================
    // Ajout d'un élément dans la table de jonction ou changement de sa priorité
    // =========================================================================
    if (    contexte.statut === 'jonctionDisponibles' && eDeposable.id === 'jonctionPris'
         || contexte.statut === 'jonctionPris'        && eDeposable.id === 'jonctionPris' && priorite) {
      let uri;
      if (priorite) {
        let itemsPrisBis = [...itemsPris];
        let indice = itemsPrisBis.indexOf(parseInt(contexte.idj));
        if (indice >= 0) itemsPrisBis.splice(indice, 1);
        if (evt.target.nodeName === "SPAN") {
          if (evt.target.dataset.idj == contexte.idj) return;
          indice = itemsPrisBis.indexOf(parseInt(evt.target.dataset.idj));
          itemsPrisBis.splice(indice, 0, contexte.idj);
        } else {
          itemsPrisBis.push(contexte.idj);
        }
        uri = 'admin?entite='+entite+'&action=mf&'+entite+'_id='+itemsPrisBis.toString()+'&film_id='+film_id;
      } else {
        uri = 'admin?entite='+entite+'&action=af&'+entite+'_id='+contexte.idj+'&film_id='+film_id;
      }
      fetch(uri)
      .then (reponse => {
        if (!reponse.ok) throw { message: "Problème technique sur le serveur" };
        return reponse.json();
      })
      .then (retour => {
        if (retour) {
          
          // Réaffichage de la liste avec ses priorités
          // ==========================================
          if (priorite) {
            let listeSpans = document.querySelectorAll('#jonctionPris span');
            listeSpans.forEach(e => e.remove());
            itemsPris = []; 
            retour.forEach(item => {
              let nom = entites[entite].champs.reduce(
                (nom, champ) => nom + item[champ] + ' ',
                ''
              );
              itemsPris.push(item[entite + '_id']);
              let spanHTML = '<span data-idj=' + item[entite + '_id'] + '>' + ( priorite ? item[priorite] + ' - ' : '') + nom + '</span>'
              eJonctionPris.insertAdjacentHTML('beforeend', spanHTML);
            });
            listeSpans = document.querySelectorAll('#jonctionPris span');
            listeSpans.forEach(e => appliquerLeGlisser(e));
            divEntite.innerText = itemsPris.length;
            let eSpan = document.querySelector('[data-idj="'+contexte.idj+'"]');
            if (eSpan.parentNode.id == "jonctionDisponibles") eSpan.remove();

          // Réaffichage de la liste triée par ordre alphabétique
          // ====================================================   
          } else {
            let eSpan = document.querySelector('[data-idj="'+contexte.idj+'"]');
            let listeDeposable = [...eDeposable.querySelectorAll('span')];
            listeDeposable.push(eSpan);
            listeDeposable.sort((e1, e2) => e1.innerText.localeCompare(e2.innerText));
            listeDeposable.forEach(e => eDeposable.appendChild(e));
            divEntite.innerText++;
          }
        }
      })
      .catch((e) => {
        eMessageErreurJonction.innerHTML = "Erreur: " + e.message;
      });
    }

    // ==================================================
    // Suppression d'un élément dans la table de jonction
    // ==================================================
    if (contexte.statut === 'jonctionPris' && eDeposable.id === 'jonctionDisponibles') {
      let uri;
      if (priorite) {
        let itemsPrisBis = [...itemsPris];
        let indice = itemsPrisBis.indexOf(parseInt(contexte.idj));
        if (indice >= 0) itemsPrisBis.splice(indice, 1);
        uri = 'admin?entite='+entite+'&action=mf&'+entite+'_id='+itemsPrisBis.toString()+'&film_id='+film_id;
      } else {
        uri = 'admin?entite='+entite+'&action=sf&'+entite+'_id='+contexte.idj+'&film_id='+film_id;
      }
      fetch(uri)
      .then (reponse => {
        if (!reponse.ok) throw { message: "Problème technique sur le serveur" };
        return reponse.json();
      })
      .then (retour => {
        if (retour) {

          let eSpan = document.querySelector('[data-idj="'+contexte.idj+'"]');

          // Réaffichage de la liste "itemsPris" avec ses priorités s'il y en a
          // ==================================================================
          if (priorite) {
            eSpan.innerText = eSpan.innerText.split([' - '])[1];
            let listeSpans = document.querySelectorAll('#jonctionPris span');
            listeSpans.forEach(e => e.remove());
            itemsPris = []; 
            retour.forEach(item => {
              let nom = entites[entite].champs.reduce(
                (nom, champ) => nom + item[champ] + ' ',
                ''
              );
              itemsPris.push(item[entite + '_id']);
              let spanHTML = '<span data-idj=' + item[entite + '_id'] + '>' + ( priorite ? item[priorite] + ' - ' : '') + nom + '</span>'
              eJonctionPris.insertAdjacentHTML('beforeend', spanHTML);
            });
            listeSpans = document.querySelectorAll('#jonctionPris span');
            listeSpans.forEach(e => appliquerLeGlisser(e));
            divEntite.innerText = itemsPris.length;
          }

          // Réaffichage de la liste "itemDisponibles" triée par ordre alphabétique
          // ======================================================================               
          let listeDeposable = [...eDeposable.querySelectorAll('span')];
          listeDeposable.push(eSpan);
          listeDeposable.sort((e1, e2) => e1.innerText.localeCompare(e2.innerText));
          listeDeposable.forEach(e => eDeposable.appendChild(e));
          if (!priorite) divEntite.innerText--;
          if (divEntite.innerText == 0) divEntite.innerText = '';
        }
      })
      .catch((e) => {
        eMessageErreurJonction.innerHTML = "Erreur: " + e.message;
      });
    }
  });  
}