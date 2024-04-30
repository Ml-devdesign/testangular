<?php 
include('header-init.php');
try {
    // $json = file_get_contents('php://input')
    $json = $_POST['article'];
    
    // Le convertie en Objet PHP
    $article = json_decode($json);//oBJET  avec trois description => nom ...

    // echo'{"toto":"'. $article -> nom. '"}'; Test pour voir si ca fonctionne 

    if(strlen($article->nom) < 3 ){
        echo '{"message":"Le nom doit avoir au moi 3 caracteres"}';
        http_response_code(400);
        exit();
    }

    if(strlen($article->nom) > 100){
        echo '{"message":"Le nom doit avoir au max 100 caracteres"}';
        http_response_code(400);
         exit();
    }

    if($article->prix <= 0){
        echo '{"message":"Le Prix est positif"}';
        http_response_code(400);
         exit();
    }

    $nouveauNomFichier = '';

    if(isset ($_FILES['image'])){

        $image = $_FILES['image'];
        $nomFichier = $image['name'];
        $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
        //*
        
        if(!in_array($extension, ['jpg','jpeg','png'])){
            echo '{"message":"L\'extension du fichier n\'est pas autorisee"}';
            http_response_code(400);
            exit();
        }
        
        $nouveauNomFichier = date("Y-m-d-H-i-s").'-'.$nomFichier;
        
        move_uploaded_file($image['tmp_name'],'uploads/'.$nouveauNomFichier);
        
    }
    
   
    $requete =
    $connexion ->prepare(
        "INSERT INTO article(nom, description, prix, image) VALUES(:nom, :description, :prix, :image)");

    $requete -> bindParam(':nom', $article -> nom);
    $requete -> bindParam(':description', $article -> description);
    $requete -> bindParam(':prix', $article->prix);
    $requete -> bindParam(':image', $nouveauNomFichier);//pour l'edition plus complexity

    $requete -> execute();

    echo '{"message": "Article successfully"}';
    http_response_code(201);

 }catch(PDOException $error){
    echo '{"message": "'. $error->getMessage().'"}';

    http_response_code(500);//erreur dans le inspecter Réseau lorceque je met un s a la table article de la requete sql

 }

 //*
        // $extensionsAutorisees = array('jpg','jpeg','png');
        // $extensionsAutorisees = array_map('trim', $extensionsAutorisees);
        // $nouveauNomFichier = uniqid(). '.'. $extension;
        // $destination = 'images/'. $nouveauNomFichier;
        // move_uploaded_file($image['tmp_name'], $destination);