<?php
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