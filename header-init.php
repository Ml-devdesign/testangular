<?php 
header('Access-Control-Allow-Origin: *');//evite les erreur Console*
header('Content-Type: application/json');//
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, DELETE ,OPTIONS");
//$connexion = new PDO('mysql:host=localhost;dbname=backend_angular', 'root', '');
// Prend les donnees brutes de la requete

// Répondre immédiatement aux requêtes OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Aucun contenu n'est nécessaire, juste une réponse 204 (No Content)
    header("HTTP/1.1 204 No Content");
    exit;
    
}

 $connexion = new PDO('mysql:host=localhost;dbname=backend_angular', 'root', '');
 
?> 
 
