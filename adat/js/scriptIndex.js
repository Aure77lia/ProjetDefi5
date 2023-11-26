// |-------------------------> Déclaraction des variables <-------------------------| \\

var map = L.map('map').setView([45.6754, 0.1791], 9);
let btnValide = document.getElementById("btnValide");
let tauxCFE1 = document.getElementById("tauxCFE1");
let pourcentageTauxCFE1 = document.getElementById("pourcentageTauxCFE1");
let tauxExoneration1 = document.getElementById("tauxExoneration1");
let pourcentageExoneration1 = document.getElementById("pourcentageExoneration1");
let tauxCFE2 = document.getElementById("tauxCFE2");
let pourcentageTauxCFE2 = document.getElementById("pourcentageTauxCFE2");
let tauxExoneration2 = document.getElementById("tauxExoneration2");
let pourcentageExoneration2 = document.getElementById("pourcentageExoneration2");
let commune1 = document.getElementById("commune1");
let commune2 = document.getElementById("commune2");

let sommeBaseLocataire1A = document.getElementById("sommeBaseLocataire1A");
let totalRecette1A = document.getElementById("totalRecette1A");
let sommeBaseLocataire1N = document.getElementById("sommeBaseLocataire1N");
let totalRecette1N = document.getElementById("totalRecette1N");

let sommeBaseLocataire2A = document.getElementById("sommeBaseLocataire2A");
let totalRecette2A = document.getElementById("totalRecette2A");
let sommeBaseLocataire2N = document.getElementById("sommeBaseLocataire2N");
let totalRecette2N = document.getElementById("totalRecette2N");

let cfe = [0, 0];
let exoneration = [0, 0];



// |-------------------------> Abonnement des événements <-------------------------| \\

btnValide.addEventListener("click", getInfo);
tauxCFE1.addEventListener("input", function () {
  pourcentageTauxCFE1.value = tauxCFE1.value + " %";
  cfe[0] = tauxCFE1.value;
});
tauxExoneration1.addEventListener("input", function () {
  pourcentageExoneration1.value = tauxExoneration1.value + " %";
  exoneration[0] = tauxExoneration1.value;
});
tauxCFE2.addEventListener("input", function () {
  pourcentageTauxCFE2.value = tauxCFE2.value + " %";
  cfe[1] = tauxCFE2.value;
});
tauxExoneration2.addEventListener("input", function () {
  pourcentageExoneration2.value = tauxExoneration2.value + " %";
  exoneration[1] = tauxExoneration2.value;
});



// |-------------------------> Déclaration des fonctions <-------------------------| \\

function test() {
  fetch("recupInfoProd.php?num=" + produit.value)
    .then(response => response.json())
    .then(data => {
      text.innerHTML = "Prix : " + parseInt(data["pr_prix"]);
      if (data["pr_image"] != null) {
        img.src = data["pr_image"];
        img.hidden = false;
      }
      else {
        img.hidden = true;
      }
    });
}

function getInfo() {
  //alert("traitement/getInfo.php?ctcn1=" + ctcn[0] + "&ctcn2=" + ctcn[1] + "&cfe1=" + cfe[0] + "&cfe2=" + cfe[1] + "&exo1=" + exoneration[0] + "&exo2=" + exoneration[1]);
  fetch("traitement/getInfo.php?ctcn1=" + ctcn[0] + "&ctcn2=" + ctcn[1] + "&cfe1=" + cfe[0] + "&cfe2=" + cfe[1] + "&exo1=" + exoneration[0] + "&exo2=" + exoneration[1])
    .then(response => response.json())
    .then(data => {
      sommeBaseLocataire1A.innerHTML = data[0]["sommeBaseLocataire"].toFixed(2) + " €";
      sommeBaseLocataire1N.innerHTML = data[0]["sommeBaseLocataireNew"].toFixed(2) + " €";
      totalRecette1A.innerHTML = data[0]["totalRecette"].toFixed(2) + " €";
      totalRecette1N.innerHTML = data[0]["totalRecetteNew"].toFixed(2) + " €";

      
      sommeBaseLocataire2A.innerHTML = data[1]["sommeBaseLocataire"].toFixed(2) + " €";
      sommeBaseLocataire2N.innerHTML = data[1]["sommeBaseLocataireNew"].toFixed(2) + " €";
      totalRecette2A.innerHTML = data[1]["totalRecette"].toFixed(2) + " €";
      totalRecette2N.innerHTML = data[1]["totalRecetteNew"].toFixed(2) + " €";
    })
    ;
}


// |-------------------------> Map <-------------------------| \\


map.setMinZoom(9);


var resetButton = L.easyButton('fa-home', function () {
  // Revenir aux coordonnées et au zoom par défaut
  map.setView([45.6754, 0.1791], 9);
});

var resetButton = L.easyButton('fa-home', function () {
  // Revenir aux coordonnées et au zoom par défaut
  map.setView([45.6754, 0.1791], 9);
});

// Ajouter le bouton à la carte
resetButton.addTo(map);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

let communeCard = document.getElementById('commune-card');
let cardTitle = document.getElementById('card-title');
let cardList = document.getElementById('card-list');

let communeInfos = []; // Liste pour stocker les informations des communes
let selectedCommunes = {}; // Dictionnaire pour suivre l'état des communes sélectionnées
let ctcn = [];

// Charger le contour du département de la Charente depuis le fichier GeoJSON
fetch('communes_charente.geojson')
  .then(response => response.json())
  .then(data => {
    L.geoJSON(data, {
      style: {
        color: '#22465e',
      },
      pointToLayer: function (feature, latlng) {
        return null;
      },
      onEachFeature: function (feature, layer) {
        var inseeDigits = feature.properties['ref:INSEE'].slice(-3);

        if (inseeDigits[0] === '0') {
          inseeDigits = inseeDigits.slice(1);
        }
        if (inseeDigits[1] === '0') {
          inseeDigits = inseeDigits.slice(2);
        }

        layer.on({
          mouseover: function (event) {
            if (!selectedCommunes[feature.properties['ref:INSEE']]) {
              event.target.setStyle({
                fillColor: '#d49e29',
                fillOpacity: 0.8
              });
            }
          },
          mouseout: function (event) {
            if (!selectedCommunes[feature.properties['ref:INSEE']]) {
              event.target.setStyle({
                fillColor: '',
                fillOpacity: 0.2
              });
            }
          },
          click: function (event) {
            var insee = feature.properties['ref:INSEE'];

            document.cookie = "insee = " + insee;

            // Ajouter ou supprimer la commune de la liste des sélectionnées
            selectedCommunes[insee] = !selectedCommunes[insee];

            if (selectedCommunes[insee]) {
              // Ajouter les informations de la commune à la liste
              communeInfos.push(feature.properties);
              ctcn.push(inseeDigits);
            } else {
              // Supprimer la commune de la liste
              var indexCommunes = communeInfos.findIndex(info => info['ref:INSEE'] === insee);
              var indexInfos = ctcn.findIndex(info => info === inseeDigits);
              if (indexCommunes !== -1) {
                communeInfos.splice(indexCommunes, 1);
              }
              if (indexInfos !== -1) {
                ctcn.splice(indexInfos, 1);
              }
            }

            // Rafraîchir l'affichage des informations des communes dans la carte Bootstrap
            showCommuneInfo();

          }
        });
      }
    }).addTo(map);
  })
  .catch(error => console.error('Erreur lors du chargement du fichier GeoJSON:', error));

function showCommuneInfo() {
  // Effacer le contenu précédent
  cardList.innerHTML = '';

  // Afficher le titre
  cardTitle.textContent = 'Informations des communes';

  // Ajouter chaque commune à la liste
  communeInfos.forEach((info, index) => {
    var communeItem = document.createElement('div');
    communeItem.innerHTML = '<b>Nom de la commune:</b> ' + info.name + '<br>' +
      '<b>Population:</b> ' + info.population + '<br>' +
      '<b>Code postal:</b> ' + info.postal_code + '<br>' +
      '<b>INSEE:</b> ' + info['ref:INSEE'] + '<br>' +
      '<hr>';
    cardList.appendChild(communeItem);

    if (index == 0){
      commune1.innerHTML = "<i class='fa-solid fa-map-pin goldFont'></i> Commune 1 : " + info.name;  
    }
    if (index == 1){
      commune2.innerHTML = "<i class='fa-solid fa-map-pin goldFont'></i> Commune 2 : " + info.name;  
    }
    if (index == 0 && commune1.value == commune2.value){
      commune2.innerHTML = "<i class='fa-solid fa-map-pin goldFont'></i> Commune 2 :";
    }


    // Mettre à jour le style de la commune sur la carte Leaflet
    var layer = map._layers[info['ref:INSEE']];
    if (layer) {
      layer.setStyle({
        fillColor: '#22465e',
        fillOpacity: 0.6
      });
    }
  });

  communeCard.style.display = 'block'; // Afficher la carte Bootstrap
}