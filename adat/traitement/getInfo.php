<?php

function getEffectifs($unCTCN, $deuxCTCN){
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete= "with aCommuns as (SELECT DISTINCT SUBSTR(CNAC3,1,2) as NACE ,nace.libelleNAF as LIBELLE
    from c1
    inner join nace
    on nace.code like SUBSTR(CNAC3,1,2)
    WHERE CTCN = :unCTCN
    INTERSECT(SELECT DISTINCT SUBSTR(CNAC3,1,2) as NACE ,nace.libelleNAF as LIBELLE
        from c1
        inner join nace
        on nace.code like SUBSTR(CNAC3,1,2)
        WHERE CTCN = :deuxCTCN)
    )select count(*),SUBSTR(CNAC3,1,2), ac.LIBELLE
                  from aCommuns ac
                  inner join c1 
                  on ac.NACE like SUBSTR(CNAC3,1,2)
                  group by SUBSTR(CNAC3,1,2);";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->bindParam(':deuxCTCN', $deuxCTCN, PDO::PARAM_INT);
    $statement->execute();
    $lesEffectifs = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $lesEffectifs;
}


function getCFE($unCTCN, $unCTCNIA) {
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT TXCNT0, TXCNA0, TXCNU0 FROM a1 WHERE CTCN = :unCTCN AND CTCNIA = :unCTCNIA";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->bindParam(':unCTCNIA', $unCTCNIA, PDO::PARAM_INT);
    $statement->execute();
    $lesCFE = $statement->fetch(PDO::FETCH_ASSOC);

    $retour = null;
    if ($lesCFE["TXCNT0"] != 0) {
        $retour = $lesCFE["TXCNT0"];
    }
    else if ($lesCFE["TXCNA0"] != 0) {
        $retour = $lesCFE["TXCNA0"];
    }
    else {
        $retour = $lesCFE["TXCNU0"];
    }
    return $retour;
}

function conversionCFE($unCFE){
    return $unCFE / 10000000 ;
}

function calculSommeFrais($unCTCN, $unCTCNIA) {
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT CTCN, CTCNIA, MNCHFI, MNTPFG, MNCHFM FROM P2 WHERE CTCN = :unCTCN AND CTCNIA = :unCTCNIA";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->bindParam(':unCTCNIA', $unCTCNIA, PDO::PARAM_INT);
    $statement->execute();
    $lesFrais = $statement->fetchAll(PDO::FETCH_ASSOC);

    $retour = 0;
    foreach ($lesFrais as $unFrais) {
        $retour += $unFrais["MNCHFI"] + $unFrais["MNTPFG"] + $unFrais["MNCHFM"];
    }
    
    return $retour;
}



function calculSommeBaseLocative2($unCTCN){
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT COUNT(*) FROM A1 WHERE CTCN = :unCTCN ";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->execute();
    $total1 = $statement->fetch(PDO::FETCH_ASSOC);

    $retour = 0 ;

    if ($total1 == 1) {
        $retour = calculTotalRecette1($unCTCN, "0") ;
    } else {
        $requete2 = "SELECT CTCNIA FROM a1 WHERE a1.CTCN = :unCTCN";
        $statement2 = $bdd->prepare($requete2);
        $statement2->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
        $statement2->execute();
        $lesDoublons = $statement2->fetchall(PDO::FETCH_ASSOC);

        foreach ($lesDoublons as $unDoublon) {
            $retour += calculSommeBaseLocative1($unCTCN, $unDoublon['CTCNIA']) ;
        }
    }
        return $retour ; 
}

function calculSommeBaseLocative1($unCTCN, $unCTCNIA) {
   
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT CTCN, CTCNIA, NCCO, VBETF0, MOBS20, CNEX01, CNEX02, MNTPXC, MNBSMA FROM baselocative WHERE CTCN = :unCTCN AND CTCNIA = :unCTCNIA";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->bindParam(':unCTCNIA', $unCTCNIA, PDO::PARAM_INT);
    $statement->execute();
    $lesValeursLocatives = $statement->fetchall(PDO::FETCH_ASSOC);

    $retour = 0;
    foreach ($lesValeursLocatives as $uneValeurLocative) {
        $total = 0;
    if ($uneValeurLocative["VBETF0"] != 0) {
        $total = $uneValeurLocative["VBETF0"];
    }
    else if ($uneValeurLocative["CNEX01"] == null && $uneValeurLocative["CNEX02"] == null) {
        $total = $uneValeurLocative["MNBSMA"];
    }
    else {
        $total = $uneValeurLocative["MNTPXC"];
    }
        $retour += $total;
        //echo getValeurLocative($unCTCN, $unCTCNIA, $uneEntr["NCCO"]) . "<br/>"
    
    }
    return $retour ; 
}


function calculTotalRecette2($unCTCN) {
    $bdd = new PDO('mysql:host=localhost; dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT COUNT(*) FROM A1 WHERE CTCN = :unCTCN ";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->execute();
    $total1 = $statement->fetch(PDO::FETCH_ASSOC);

    $retour = 0 ;

    if ($total1 == 1) {
        $retour = calculTotalRecette1($unCTCN, "0") ;
    } else {
        $requete2 = "SELECT CTCNIA FROM a1 WHERE a1.CTCN = :unCTCN";
        $statement2 = $bdd->prepare($requete2);
        $statement2->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
        $statement2->execute();
        $lesDoublons = $statement2->fetchall(PDO::FETCH_ASSOC);

        foreach ($lesDoublons as $unDoublon) {
            $retour += calculTotalRecette1($unCTCN, $unDoublon['CTCNIA']) ;
        }
    }
    
    return $retour ;
}

function calculTotalRecette1($unCTCN, $unCTCNIA){
    $retour = 0 ;

    $retour = calculSommeBaseLocative1($unCTCN, $unCTCNIA)*conversionCFE(getCFE($unCTCN, $unCTCNIA)) + calculSommeFrais($unCTCN, $unCTCNIA) ; 
    return $retour ;
}


function calculNewSommeBaseLocative2($unCTCN, $newExo){
    return calculSommeBaseLocative2($unCTCN)*$newExo*0.01 ;
}


function calculNewTotalRecette2($unCTCN, $newCFE, $unNewExo){
    $bdd = new PDO('mysql:host=localhost; dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT COUNT(*) FROM A1 WHERE CTCN = :unCTCN ";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->execute();
    $total1 = $statement->fetch(PDO::FETCH_ASSOC);

    $retour = 0 ;

    if ($total1 == 1) {
        $retour = calculNewTotalRecette1($unCTCN, "0", $newCFE, $unNewExo) ;
    } else {
        $requete2 = "SELECT CTCNIA FROM a1 WHERE a1.CTCN = :unCTCN";
        $statement2 = $bdd->prepare($requete2);
        $statement2->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
        $statement2->execute();
        $lesDoublons = $statement2->fetchall(PDO::FETCH_ASSOC);

        foreach ($lesDoublons as $unDoublon) {
            $retour += calculNewTotalRecette1($unCTCN, $unDoublon['CTCNIA'], $newCFE, $unNewExo) ;
        }
    }
    
    return $retour ;
}



function calculNewTotalRecette1($unCTCN, $unCTCNIA, $unNewCFE, $unNewExo) {
    return calculNewSommeBaseLocative2($unCTCN, $unNewExo)*$unNewCFE*0.01+calculSommeFrais($unCTCN, $unCTCNIA) ;
}


$pourNico = array(
    array("CTCN1" =>"", "sommeBaseLocataire" => "", "sommeBaseLocataireNew" => "", "totalRecette" => "", "totalRecetteNew" => ""),
    array("CTCN2" =>"", "sommeBaseLocataire" => "", "sommeBaseLocataireNew" => "", "totalRecette" => "", "totalRecetteNew" => "")
) ;



//---------------------------------------



if (isset($_GET['ctcn1']) && isset($_GET['ctcn2']) && isset($_GET['cfe1']) && isset($_GET['cfe2']) && isset($_GET['exo1']) && isset($_GET['exo2'])) {
    $ctcn1 = $_GET['ctcn1'];
    $ctcn2 = $_GET['ctcn2'];
    $cfe1 = $_GET['cfe1'];
    $cfe2 = $_GET['cfe2'];
    $exo1 = $_GET['exo1'] ;
    $exo2 = $_GET['exo2'] ;

    $pourNico[0]["CTCN1"]=$ctcn1;
    $pourNico[1]["CTCN2"]=$ctcn2;
    $pourNico[0]["sommeBaseLocataire"]=calculSommeBaseLocative2($ctcn1);
    $pourNico[1]["sommeBaseLocataire"]=calculSommeBaseLocative2($ctcn2);
    $pourNico[0]["totalRecette"]=calculTotalRecette2($ctcn1) ;
    $pourNico[1]["totalRecette"]=calculTotalRecette2($ctcn2) ;
    $pourNico[0]["sommeBaseLocataireNew"]= calculNewSommeBaseLocative2($ctcn1, $exo1) ;
    $pourNico[1]["sommeBaseLocataireNew"]=calculNewSommeBaseLocative2($ctcn2, $exo2) ;
    $pourNico[0]["totalRecetteNew"]=calculNewTotalRecette2($ctcn1, $cfe1, $exo1) ;
    $pourNico[1]["totalRecetteNew"]=calculNewTotalRecette2($ctcn2, $cfe2, $exo2) ;
    
    echo json_encode($pourNico);
}
