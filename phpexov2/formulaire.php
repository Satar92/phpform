<?php

include_once('./init.inc.php');

// recuperation de tous les employés
try {
    $stmt = $pdo->query("SELECT * FROM annuaire");
} catch (PDOException $e) {
    afficheErreur($e->getMessage());
}
$enreg = $stmt->fetchAll();

// debug

if ($env === 'dev') {
    showVar($enreg);
}

// tableau des nom des champs de formulaire
$formFieldNames = [
    "nom",
    "prenom",
    "sexe",
    "profession",
    "codepostal",
    "telephone",
    "ville",
    "adresse",
    "date_de_naissance",
    "description",
];
// traitement du formulaire
// récupération des données:
if (isset($_POST['submitBtn'])) {
    // le bouton de soumission du formulaire a été cliqué
    extract($_POST);
    // Validation des données
    // verif prenom
    if (!validateData($nom, TYPE_STRING) || preg_match('/\d/', $nom) || preg_match('/[^A-Za-z\s]/', $nom)) {
        $errors['nom'] = "Le champs 'nom' n'est pas valide!";
    }
    if (!validateData($prenom, TYPE_STRING) || preg_match('/\d/', $prenom) || preg_match('/[^A-Za-z\s]/', $prenom)) {
        $errors['prenom'] = "Le champs 'prenom' n'est pas valide!";
    }
    if (!is_numeric($telephone)) {
        $errors['telephone'] = "Le champs 'telephone' n'est pas valide!";
    }
    if (strlen((string)$telephone) != 10) {
        $errors['telephoneL'] = "Le champs 'telephone' doit comporter 10 chiffres!";
    }

    if (!validateData($profession, TYPE_STRING) || preg_match('/\d/', $profession) || preg_match('/[^A-Za-z\s]/', $profession)) {
        $errors['profession'] = "Le champs 'profession' n'est pas valide!";
    }

    if (!is_numeric($telephone)) {
        $errors['codepostal'] = "Le champs 'codepostal' n'est pas valide!";
    }

    if (strlen((string)$codepostal) != 5) {
        $errors['codepostall'] = "Le champs 'codepostal' doit comporter 5 chiffres!";
    }
    if (!validateData($adresse, TYPE_STRING)) {
        $errors['adresse'] = "Le champs 'adresse' n'est pas valide!";
    }

    if (!in_array($sexe, ['m', 'f'], true)) {
        $errors['sexe'] = "Le champs 'genre' n'est pas valide!";
    }
    if (!validateData($description, TYPE_STRING)) {
        $errors['description'] = "Le champs 'description' n'est pas valide!";
    }
    if (!count($errors)) {
        // pas d'erreur de validation trouvées

        // on prepare la requete
        $stmt = $pdo->prepare("
            INSERT INTO annuaire (
                id_annuaire	,
                nom,
                prenom,
                sexe,
                profession,
                codepostal,
                telephone,
                ville,
                adresse,
                date_de_naissance,
                description
            )
            VALUES (
                null,
                :nom,
                :prenom,
                :sexe,
                :profession,
                :codepostal,
                :telephone,
                :ville,
                :adresse,
                :date_de_naissance,
                :description
            )
        ");

        // on relie les marqueurs de position aux valeurs à vérifier puis inserer
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':sexe', $sexe, PDO::PARAM_STR);
        $stmt->bindParam(':profession', $profession, PDO::PARAM_STR);
        $stmt->bindParam(':codepostal', $codepostal, PDO::PARAM_INT);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_INT);
        $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':date_de_naissance', $date_de_naissance, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);


        // on execute la requete
        try {
            $stmt->execute();
            $lastInsertedId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($env === 'dev') {
                $errors[] = $e->getMessage();
            } else {
                $errors[] = "Une erreur inattendue est survenue";
            }
        }
        if (!count($errors) && isset($lastInsertedId)) {
            $success[] = 'La personne a été ajouté';
            resetFormValues($formFieldNames);
        }
    }
}
$formValue = getFormValues($formFieldNames);








// inclusion header html 
include_once('./includes/header.php');
?>

<!-- Contenu de la page -->

<h1 style="margin: 0 auto; text-align: center;">Formulaire</h1>
<!-- Zonne de notif erreur -->
<?php if (count($errors)) : ?>
    <div class="w100 mt-3 form-error-container">
        <ul class="">
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif ?>

<!-- zone de notif succes -->
<?php if (count($success)) : ?>

    <div class="w100 mt-3 form-success-container">
        <ul class="">
            <?php foreach ($success as $succes) : ?>
                <li><?= $succes ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif ?>
<div class="border border-black border-2 rounded col-md-3" style="margin: 0 auto; text-align: center;">
    <form method="POST">
        <label for="nom">Nom *</label>
        <input class="form-control" type="text" id="nom" name="nom" value="<?= $formValue['nom'] ?>" required><br>

        <label for="prenom">Prénom *</label>
        <input class="form-control " type="text" id="prenom" name="prenom" value="<?= $formValue['prenom'] ?>" required><br>

        <label for="telephone">Téléphone *</label>
        <input class="form-control  " type="tel" id="telephone" name="telephone" value="<?= $formValue['telephone'] ?>" required><br>

        <label for="profession">Profession</label>
        <input class="form-control  " type="text" id="profession" name="profession" value="<?= $formValue['profession'] ?>"><br>

        <label for="ville">Ville</label>
        <input class="form-control  " type="text" id="ville" name="ville" value="<?= $formValue['ville'] ?>"><br>

        <label for="codepostal">Code postal</label>
        <input class="form-control  " type="text" id="codepostal" name="codepostal" value="<?= $formValue['codepostal'] ?>"><br>

        <label for="adresse">Adresse</label>
        <input class="form-control  " type="text" id="adresse" name="adresse" value="<?= $formValue['adresse'] ?>"><br>

        <label for="date_de_naissance">Date de naissance</label>
        <input class="form-control  " type="date" id="date_de_naissance" name="date_de_naissance" value="<?= $formValue['date_de_naissance'] ?>"><br>

        <label>Sexe :</label>
        <label for="sexe_homme">Homme</label>
        <input type="radio" id="sexe_homme" name="sexe" value="m" <?= $formValue['sexe'] === 'm' ? 'checked' : '' ?>>
        <label for="sexe_femme">Femme</label>
        <input type="radio" id="sexe_femme" name="sexe" value="f" <?= $formValue['sexe'] === 'f' ? 'checked' : '' ?>><br>

        <label for="description">Description</label>
        <textarea class="form-control text-center" id="description" name="description"><?= $formValue['description'] ?></textarea><br>

        <div class="text-center">
            <input name="submitBtn" class="btn btn-primary" type="submit" value="Enregistrement">
        </div>
    </form>
</div>
<br>
<div class="text-center border border-success border-2 rounded col-md-3" style="margin: 0 auto; text-align: center;">
    <a class=" navbar-brand text-dark " href="../phpexov2/affichage_annuaire.php">Afficher Annuaire</a>

</div>





<!-- /Contenu de la page -->


<?php
include_once('./includes/footer.php');
?>