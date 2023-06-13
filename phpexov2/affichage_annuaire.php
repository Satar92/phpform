<?php

include_once('./functions/functions.php');
include_once('./init.inc.php');
include_once('./config/DB/dbdata.inc.php');
include_once('./functions/constants.php');

include_once('./includes/header.php');


// suppresion de l'employé
$stmt = $pdo->prepare("DELETE FROM annuaire WHERE id_annuaire = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();


// Récupération des données de la table "annuaire"  
$sql = "SELECT * FROM annuaire";
$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    // Requête pour compter le nombre d'hommes
    $sqlHommes = "SELECT COUNT(*) as total_hommes FROM annuaire WHERE sexe = 'm'";
    $resultHommes = $pdo->query($sqlHommes);
    $rowHommes = $resultHommes->fetch(PDO::FETCH_ASSOC);
    $totalHommes = $rowHommes['total_hommes'];

    // Requête pour compter le nombre de femmes
    $sqlFemmes = "SELECT COUNT(*) as total_femmes FROM annuaire WHERE sexe = 'f'";
    $resultFemmes = $pdo->query($sqlFemmes);
    $rowFemmes = $resultFemmes->fetch(PDO::FETCH_ASSOC);
    $totalFemmes = $rowFemmes['total_femmes'];

    $nbCols = 0;

    // Affichage des informations contenues dans la table
    echo "<h2>Informations :</h2>";

    echo "<table class='table table-bordered'>";
    echo "<tr>";
    echo "<th>id</th>";
    echo "<th>nom</th>";
    echo "<th>prenom</th>";
    echo "<th>telephone</th>";
    echo "<th>profession</th>";
    echo "<th>ville</th>";
    echo "<th>code postal</th>";
    echo "<th>adresse</th>";
    echo "<th>date de naissance</th>";
    echo "<th>sexe</th>";
    echo "<th>description</th>";
    echo "<th>Action</th>"; // Ajout d'une colonne pour les actions
    echo "</tr>";

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td class='border border-secondary p-2'>" . $row['id_annuaire'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['nom'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['prenom'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['telephone'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['profession'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['ville'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['codepostal'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['adresse'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['date_de_naissance'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['sexe'] . "</td>";
        echo "<td class='border border-secondary p-2'>" . $row['description'] . "</td>";

        // Ajout des boutons Modifier et Supprimer avec les ID correspondants
        echo "<td class='border border-secondary p-2'>";
        echo "<a href='./update.php?id=" . $row['id_annuaire'] . "'>";
        echo "<button type='button' class='btn btn-primary'>Modifier</button>";
        echo "</a>";
        echo "</td>";

        echo "<td class='border border-secondary p-2'>";
        echo "<form method='POST' action='delete.php' onsubmit=\"return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?');\">";
        echo "<input type='hidden' name='id' value='" . $row['id_annuaire'] . "'>";
        echo "<button type='submit' class='btn btn-danger' name='delete'>Supprimer</button>";
        echo "</form>";
        echo "</td>";


        echo "</tr>";
    }


    echo "</table>";


    echo "<tr>";
    echo "<td colspan='" . $nbCols . "' class='border border-secondary p-2'>Nombre de femme(s) : " . $totalFemmes . "</td><br/>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='" . $nbCols . "' class='border border-secondary p-2'>Nombre d'homme(s) : " . $totalHommes . "</td><br/>";
    echo "</tr>";

    echo "</table>";
} else {
    echo "Aucune donnée trouvée dans la table 'annuaire'.";
}
echo '<div class="text-center border border-success border-2 rounded col-md-3" style="margin: 0 auto; text-align: center;">';
echo '    <a class="navbar-brand text-dark" href="../phpexov2/formulaire.php">Ajouter</a>';
echo '</div>';

// Fermeture de la connexion
$pdo = null;
include_once('./includes/footer.php');
