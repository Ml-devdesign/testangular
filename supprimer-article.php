<?php 
include('header-init.php');

// delete the aarticles from the database and remove them from the list of articles that  have been deleted 
$idArticleSupprimer= $_GET['id'];

$requete = $connexion->prepare('DELETE FROM article WHERE id = :id');

$requete->bindValue('id',$idArticleSupprimer);
$requete->execute();
echo '{"message":"L\'article a bien été supprimer"}';


?>