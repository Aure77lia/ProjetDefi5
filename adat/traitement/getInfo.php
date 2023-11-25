<?php

//calculTotalRecette("15", "0") ;

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
    return $unCFE / 100000 ;
}

/*
function getValeurLocative($unCTCN, $unCTCNIA, $unNCCO) {
    $bdd = new PDO('mysql:host=localhost;dbname=adat;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT CTCN, CTCNIA, NCCO, VBETF0, MOBS20, CNEX01, CNEX02, MNTPXC, MNBSMA FROM baselocative WHERE CTCN = :unCTCN AND CTCNIA = :unCTCNIA AND NCCO = :unNCCO";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':unCTCN', $unCTCN, PDO::PARAM_INT);
    $statement->bindParam(':unCTCNIA', $unCTCNIA, PDO::PARAM_INT);
    $statement->bindParam(':unNCCO', $unNCCO, PDO::PARAM_INT);
    $statement->execute();
    $lesValeursLocatives = $statement->fetch(PDO::FETCH_ASSOC);

    
    $retour = null;
    if ($lesValeursLocatives["VBETF0"] != 0) {
        $retour = $lesValeursLocatives["VBETF0"];
    }
    else if ($lesValeursLocatives["CNEX01"] == null && $lesValeursLocatives["CNEX02"] == null) {
        $retour = $lesValeursLocatives["MNBSMA"];
    }
    else {
        $retour = $lesValeursLocatives["MNTPXC"];
    }
    
    return $retour;
}
*/

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



function calculSommeBaseLocative($unCTCN, $unCTCNIA) {
   
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

    
    return $retour;
}




function calculTotalRecette1($unCTCN, $unCTCNIA){
    $retour = 0 ;

    $retour = calculSommeBaseLocative($unCTCN, $unCTCNIA)*conversionCFE(getCFE($unCTCN, $unCTCNIA)) + calculSommeFrais($unCTCN, $unCTCNIA) ; 
    echo("ouet") ;
    return $retour ;
}

/*

Fonction calculSommeFrais(unCTCN, unCTCNIA) {
    $s = 0
SELECT CTCN, CTCNIA, MNCHFI, MNTPFG, MNCHFM FROM P2 WHERE CTCN = :unCTCN AND CTCNIA = :unCTCNIA
    le mettre dans un tableau Frais 

    For each  in Frais as frais {
        $s+=Frais[0] + Frais[1] + Frais[2]

    }


}

*/




if (isset($_GET['num']) && isset($_GET['commune'])) {
    $num = $_GET['num'];
    $commune = $_GET['commune'];

    
    $bdd = new PDO('mysql:host=localhost;dbname=ajax;charset=UTF8', 'root', 'root') or die('Erreur connexion à la base de données');
    $requete = "SELECT * FROM produit WHERE pr_id = :num";
    $statement = $bdd->prepare($requete);
    $statement->bindParam(':num', $num, PDO::PARAM_INT);
    $statement->execute();
    $lesProduits = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($lesProduits);
}
