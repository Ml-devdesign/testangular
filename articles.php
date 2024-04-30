<?php 
// header('Access-Control-Allow-Origin: *');//evite les erreur Console
// $connexion = new PDO('mysql:host=localhost;dbname=backend_angular', 'root', '');
include('header-init.php');
//si l'authorisation n'existe pas il renvoie un 403
//Si l'en-tête Authorization n'existe pas on renvoie une erreur 403

$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    http_response_code(403);
    echo '{"message":"Vous n\'etes pas connecté"}';
    exit;
}

$jwt = $headers['Authorization'];

$jwtParts = explode('.', $jwt);

$enTete = $jwtParts[0];
$corp = $jwtParts[1];
$signature = $jwtParts[2];

$signatureRecalcule = hash_hmac('sha256', "$enTete.$corp", 'votre_cle_secrete', true);
$signatureRecalculeBase64 = rtrim(strtr(base64_encode($signatureRecalcule), '+/', '-_'), '=');
// le resultat et dans le inspecte appli stockage local http://local:4200 => jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MiwiYWRtaW4iOjEsImVtYWlsIjoiYUBhLmNvbSJ9.mA7ckYgAnrwfMsoEfoNzwTykpXApQ0fFPqhVZP7epaw 

if ($signature !== $signatureRecalculeBase64) {
    http_response_code(403);
    echo '{"message":"signature invalide"}';
    exit;
}


$requete = $connexion->query('SELECT * FROM article');

$articles = $requete->fetchAll();

echo json_encode($articles);
 
























// $headers = apache_request_headers();

// if ($signature !== $signatureRecalculeBase64) {
//     http_response_code(403);
//     echo '{"message":"signature invalide"}';
//     exit;
// }
// if (!isset($headers['Authorization'])) {
//     http_response_code(403);
//     echo '{"message":"Vous n\'etes pas connecté"}';
//     exit;
// }

// $jwt = $headers['Authorization'];

// // echo $jwt; pour la verification du code de lerreur

// $jwtParts = explode(' ', $jwt);//permet de cree un tableau des suite avec des point 
// $enTete = $jwtParts[0];
// $corp = $jwtParts[1];
// $signature = $jwtParts[2];

// $signatureRecalcule = hash_hmac('sha256', "$enTete.$corp", 'votre_cle_secrete', true);
// $signatureRecalculeBase64 = rtrim(strtr(base64_encode($signatureRecalcule), '+/', '-_'), '=');


// $requete = $connexion->query('SELECT * FROM article');

// $articles = $requete->fetchAll();

// echo json_encode($articles);
