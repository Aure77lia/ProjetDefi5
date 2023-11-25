var map = L.map('map').setView([45.6754, 0.1791], 9);
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

var communeCard = document.getElementById('commune-card');
var cardTitle = document.getElementById('card-title');
var cardList = document.getElementById('card-list');

var communeInfos = []; // Liste pour stocker les informations des communes
var selectedCommunes = {}; // Dictionnaire pour suivre l'état des communes sélectionnées

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
            } else {
              // Supprimer la commune de la liste
              var index = communeInfos.findIndex(info => info['ref:INSEE'] === insee);
              if (index !== -1) {
                communeInfos.splice(index, 1);
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



// |-------------------------> Déclaraction des variables <-------------------------| \\

let btnValide = document.getElementById("btnValide");
let tauxCFE = document.getElementById("tauxCFE");
let pourcentageTauxCFE = document.getElementById("pourcentageTauxCFE");
let exoneration1 = document.getElementById("exoneration1");
let exoneration2 = document.getElementById("exoneration2");


// |-------------------------> Abonnement des événements <-------------------------| \\

btnValide.addEventListener("click", recupProduit);
tauxCFE.addEventListener("input", function () {
  pourcentageTauxCFE.value = tauxCFE.value + " %";
});


// |-------------------------> Déclaration des fonctions <-------------------------| \\

function recupInfo() {
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