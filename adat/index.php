<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADAT</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrapPerso.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.4.0/src/easy-button.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" sizes="250x250" href="image/logoDGFIP.png">

</head>

<body>
    <div>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar fixed-top navbar-expand-sm navbar-light bg-22465e">
                        <div class="container-fluid">
                            <div class="collapse navbar-collapse" id="collapsibleNavId">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#" aria-current="page">ADAT<span class="visually-hidden">(current)</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#donnees" aria-current="page">Données<span class="visually-hidden">(current)</span></a>
                                    </li>
                                </ul>
                                <!--<form class="d-flex my-2 my-lg-0">
                                    <li class="nav-item dropdown me-auto">
                                        <a class="nav-link text-white dropdown-toggle" href="#" id="dropdownId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownId">
                                            <a class="dropdown-item" href="#">Action 1</a>
                                            <a class="dropdown-item" href="#">Action 2</a>
                                        </div>
                                    </li>
                                </form> -->
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <br /><br /><br />
        <div id="fond">
            <div class="container-fluid" id="adat">
                <div class="row">
                    <div class="col-12 col-sm-8">
                        <div id="map"></div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card" id="parametre">
                            <div class="card-body">
                                <h1 class="text-center goldTitle">Paramètres</h1>
                                <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
                                <p class="card-text  text-center">Veuillez choisir les paramètres à varier</p>

                                <h4 id="commune1"><i class="fa-solid fa-map-pin goldFont"></i> Commune 1</h4>
                                <label class="form-label" for="tauxCFE1">Taux CFE <output id="pourcentageTauxCFE1">0 %</output></label>
                                <div class="range">
                                    <input type="range" class="form-range" id="tauxCFE1" min="0" max="100" value="0" />
                                </div>
                                <label class="form-label" for="tauxExoneration1">Exonération <output id="pourcentageExoneration1">0 %</output></label>
                                <div class="range">
                                    <input type="range" class="form-range" id="tauxExoneration1" min="0" max="100" value="0" />
                                </div>
                                <br />

                                <h4 id="commune2"><i class="fa-solid fa-map-pin goldFont"></i> Commune 2</h4>
                                <label class="form-label" for="tauxCFE2">Taux CFE <output id="pourcentageTauxCFE2">0 %</output></label>
                                <div class="range">
                                    <input type="range" class="form-range" id="tauxCFE2" min="0" max="100" value="0" />
                                </div>
                                <label class="form-label" for="tauxExoneration2">Exonération <output id="pourcentageExoneration2">0 %</output></label>
                                <div class="range">
                                    <input type="range" class="form-range" id="tauxExoneration2" min="0" max="100" value="0" />
                                </div>

                                <!--<div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="exoneration1">
                                    <label class="form-check-label" for="exoneration1">
                                        Code nature exonération 1
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="exoneration2">
                                    <label class="form-check-label" for="exoneration2">
                                        Code nature exonération 2
                                    </label>
                                </div>-->
                                <center><a name="" id="btnValide" class="btn btn-primary" href="#donnees" role="button"><i class="fa-solid fa-check"></i> Valider</a></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card" id="communeSelectionnee">
                            <div class="card-body">
                                <div class="container mt-3" id="commune-info">
                                    <div class="card mb-3" id="commune-card">
                                        <div class="card-body">
                                            <h5 class="card-title" id="card-title"></h5>
                                            <div id="card-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br />

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card" id="donnees">
                            <div class="card-body">
                                <h1 class="text-center goldTitle">Données</h1>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <br>
                                        <div class="card" id="donneeCommune1">
                                            <div class="card-body">
                                                <h3 class="text-center">Commune 1</h3>
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <center><h5>Ancienne</h5></center><br/>
                                                        Somme base locataire : <i id="sommeBaseLocataire1A"></i><br/><br/>
                                                        Total recette : <i id="totalRecette1A"></i>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <center><h5>Nouvelle</h5></center><br/>
                                                        Somme base locataire : <i id="sommeBaseLocataire1N"></i><br/><br/>
                                                        Total recette : <i id="totalRecette1N"></i>
                                                    </div>
                                                </div>
                                                <br>
                                                <center><a name="" id="btnCopier1" class="btn btn-primary btnCopier" role="button"><i class="fa-solid fa-copy"></i> Copier</a></center>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <br>
                                        <div class="card" id="donneeCommune2">
                                            <div class="card-body">
                                                <h3 class="text-center">Commune 2</h3>
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <center><h5>Ancienne</h5></center><br/>
                                                        Somme base locataire : <i id="sommeBaseLocataire2A"></i><br/><br/>
                                                        Total recette : <i id="totalRecette2A"></i>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <center><h5>Nouvelle</h5></center><br/>
                                                        Somme base locataire : <i id="sommeBaseLocataire2N"></i><br/><br/>
                                                        Total recette : <i id="totalRecette2N"></i>
                                                    </div>
                                                </div>
                                                <br>
                                                <center><a name="" id="btnCopier2" class="btn btn-primary btnCopier" role="button"><i class="fa-solid fa-copy"></i> Copier</a></center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-easybutton@2.4.0/src/easy-button.js"></script>
        <script src="js/scriptIndex.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>