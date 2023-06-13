<?php

// Inclure les fichiers nécessaires et établir la connexion à la base de données
include_once("./init.inc.php");
// Vérifier si le formulaire a été soumis et si l'ID est présent
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Requête DELETE
    $stmt = $pdo->prepare("DELETE FROM annuaire WHERE id_annuaire = :id");

    // Liaison des paramètres
    $stmt->bindParam(':id', $id);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Redirection vers la page d'affichage des données ou affichage d'un message de succès
        header('Location: affichage_annuaire.php');
        exit;
    } else {
        // Gestion de l'erreur en cas d'échec de la requête DELETE
        echo "Erreur lors de la suppression de l'enregistrement.";
    }
} else {
    // Redirection vers la page d'affichage des données ou affichage d'un message d'erreur
    header('location: ./affichage_annuaire.php');
    exit;
}
?>

