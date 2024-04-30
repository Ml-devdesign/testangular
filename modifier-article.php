<?php
//inclure le fichier de connexion à la base de donnée et des autorisation de controls
include("header-init.php");

//faire un try catch pour cibler les erreurs

try{
    //récupération du contenu
    //prendre les données brutes de la requête
    //$json = file_get_contents("php://input");
    $json = $_POST["article"];
    //convertir en objet php et décoder le json
    $article = json_decode($json);
    //renvoie du nouveau json avec une propriété toto avec comme valeur $article
    //echo'{"toto": "'.$article -> nom.'"}';

    

    if(!isset($_GET["id"])){
        echo '{"message": "L\'url doit contenir l\'id de l\'article"}';
        http_response_code(400);
        exit;
    }
    $idArticle = $_GET["id"];

    //verification pour le nombre de caractères entré dans le champ nom ou inférieur à 100
    if(strlen($article->nom)<= 3){
        echo '{"message":"Le nom doit avoir au moins 3 caractères"}';
        http_response_code(400);
        exit;
    } 
    
    if(strlen($article->nom) > 100){
        echo '{"message": "Le nom ne doit pas contenir plus de 100 caractères"}';
        http_response_code(400);
        exit;
    }

    //vérification du champs prix
    if(($article->prix) <=0){
        echo '{"message": "le prix ne peut pas être inférieur à 0.01 euros."}';
        http_response_code(400);
        exit;
    }

    //variable image
    $newNomFichier = "";

    //recupération image
    if(isset($_FILES["image"])) {

        $image = $_FILES["image"];
        //générer le nom du fichier => aléatoire
        $nomFichier = $image["name"];
        //récupération extension fichier
        //$splitNomFichier = explode(".",$nomFichier);
        $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
        //condition de vérification
        if(!in_array($extension, ["jpg", "jpeg", "png"])){
            echo '{"message" : "l\'extension du fichier doit être jpg, jpeg ou png"}';
            http_response_code(400);
            exit;
        }

        $newNomFichier = date("Y-m-d-H-i-s")."-".$nomFichier;
        //on deplace le fichier vers un autre dossier upload
        move_uploaded_file($image["tmp_name"], "uploads/".$newNomFichier);

    }
    

    //connexion base de donnée
    $linkbd =  new PDO("mysql:host=localhost;dbname=backend_angular;charset=utf8","root","");

    //si aucune image sélectionné et pas supprimé
    //pas d'image
    if ($newNomFichier == "" && !$article -> imageSupprime){

        $requestSql = $linkbd -> prepare("UPDATE article SET nom = :nom, description = :description, prix = :prix  WHERE id = :id");
        $requestSql -> bindValue("nom", $article -> nom);
        $requestSql -> bindValue("description", $article -> description);
        $requestSql -> bindValue("prix", $article -> prix);
        $requestSql -> bindValue("id", $idArticle);
        $requestSql -> execute();

    }  else {
        //on crée notre requête
        $requestSql = $linkbd -> prepare("UPDATE article SET nom = :nom, description = :description, prix = :prix, image = :image WHERE id = :id");
        $requestSql -> bindValue("nom", $article -> nom);
        $requestSql -> bindValue("description", $article -> description);
        $requestSql -> bindValue("prix", $article -> prix);
        $requestSql -> bindValue("image", $newNomFichier);//edition de l'image
        $requestSql -> bindValue("id", $idArticle);
        $requestSql -> execute();

    }


    //vérification 
    echo '{"message" : "Article modifié"}';
    http_response_code(200);

} catch(PDOException $e){
    //affichage de l'erreur en code status => 500 (erreur interne => console onglet Network)
    echo '{"message":"'.$e -> getMessage().'"}';
    http_response_code(500);
}