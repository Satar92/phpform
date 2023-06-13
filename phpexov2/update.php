<?php
// PAGE D'ACCUEIL
// inclusion initiale
include_once("./init.inc.php");



// recuperation de l'id de l'emploé concerné
$idAnnuaire = (int)$_GET['id'];
// recuperation de l'employé dont on a recuperer l'id 

$stmt = $pdo->prepare('SELECT * FROM annuaire WHERE id_annuaire=:id;');
$stmt->bindParam(":id", $idAnnuaire, PDO::PARAM_INT);

try {
  $stmt->execute();
} catch (PDOException $e) {
  if ($env === 'dev') {
    $errors[] = $e->getMessage();
  } else {
    $errors[] = "Une erreur inattendue est survenue";
  }
}


// recuperation de tous les employés

$annuaire = $stmt->fetch();

// debug

if ($env === 'dev') {
  incomingData();
  showVar($annuaire);
}



// formation de formValue
$formValue = $annuaire;



if (isset($_POST['updateSubmitBtn'])) {
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
    $stmt = $pdo->prepare(
      "
            UPDATE annuaire SET
            nom = :nom,
            prenom = :prenom,
            sexe = :sexe,
            profession = :profession,
            codepostal = :codepostal,
            telephone = :telephone,
            ville = :ville,
            adresse = :adresse,
            date_de_naissance = :date_de_naissance,
            description = :description
            WHERE id_annuaire = :id"


    );

    // on relie les marqueurs de position aux valeurs à vérifier puis inserer

    $stmt->bindParam(':id', $idAnnuaire, PDO::PARAM_INT);
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
    } catch (PDOException $e) {
      if ($env === 'dev') {
        $errors[] = $e->getMessage();
      } else {
        $errors[] = "Une erreur inattendue est survenue";
      }
    }
    if (!count($errors)) {
      $success[] = 'Employé correctement modifié';
      $stmt = $pdo->prepare('SELECT * FROM annuaire WHERE id_annuaire=:id;');
      $stmt->bindParam(":id", $idAnnuaire, PDO::PARAM_INT);

      try {
        $stmt->execute();
      } catch (PDOException $e) {
        if ($env === 'dev') {
          $errors[] = $e->getMessage();
        } else {
          $errors[] = "Une erreur inattendue est survenue";
        }
      }


      // recuperation de tous les employés

      $annuaire = $stmt->fetch();
      $formValue = $annuaire;
    }
  }
}

// TITRE DE LA PAGE
$titrePrincipal = 'Modifications';

// ID DU BODY 
$bodyId = 'update_annuaire';




// affichage de la page 
// header:
include_once("./includes/header.php");
?>
<!-- Contenu de la page -->
<div class="container-col">
  <div class="container-struct form-container">
    <h3 class="text-dark">Ajouter un employé</h3>

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

    <!-- bouton de retour a la liste -->
    <a href="<?= HTTP_SITE_URL ?>">
      <button type="button" class="btn btn-danger mt-3 ms-3 mb-5"> Annuler modif / Retour  </button>
    </a>

    <!-- formulaire de modification de l'employé -->
    <form method="POST" id=updateForm>
      <label for="nom">Nom *</label>
      <input class="form-control <?= isset($errors['nom']) ? 'red-border' : ' ' ?>" type="text" id="nom" name="nom" value="<?= $formValue['nom'] ?>" required><br>

      <label for="prenom">Prénom *</label>
      <input class="form-control <?= isset($errors['prenom']) ? 'red-border' : ' ' ?>" type="text" id="prenom" name="prenom" value="<?= $formValue['prenom'] ?>" required><br>

      <label for="telephone">Téléphone *</label>
      <input class="form-control <?= isset($errors['telephone']) ? 'red-border' : ' ' ?> " type="tel" id="telephone" name="telephone" value="<?= $formValue['telephone'] ?>" required><br>

      <label for="profession">Profession</label>
      <input class="form-control <?= isset($errors['profession']) ? 'red-border' : ' ' ?> " type="text" id="profession" name="profession" value="<?= $formValue['profession'] ?>"><br>

      <label for="ville">Ville</label>
      <input class="form-control <?= isset($errors['ville']) ? 'red-border' : ' ' ?> " type="text" id="ville" name="ville" value="<?= $formValue['ville'] ?>"><br>

      <label for="codepostal">Code postal</label>
      <input class="form-control <?= isset($errors['codepostal']) ? 'red-border' : ' ' ?> " type="text" id="codepostal" name="codepostal" value="<?= $formValue['codepostal'] ?>"><br>

      <label for="adresse">Adresse</label>
      <input class="form-control <?= isset($errors['adresse']) ? 'red-border' : ' ' ?> " type="text" id="adresse" name="adresse" value="<?= $formValue['adresse'] ?>"><br>

      <label for="date_de_naissance">Date de naissance</label>
      <input class="form-control <?= isset($errors['date_de_naissance']) ? 'red-border' : ' ' ?> " type="date" id="date_de_naissance" name="date_de_naissance" value="<?= $formValue['date_de_naissance'] ?>"><br>

      <label>Sexe :</label>
      <label for="sexe_homme">Homme</label>
      <input class="<?= isset($errors['nom']) ? 'red-border' : ' ' ?>" type="radio" id="sexe_homme" name="sexe" value="m" <?= $formValue['sexe'] === 'm' ? 'checked' : '' ?>>
      <label for="sexe_femme">Femme</label>
      <input class="<?= isset($errors['nom']) ? 'red-border' : ' ' ?>" type="radio" id="sexe_femme" name="sexe" value="f" <?= $formValue['sexe'] === 'f' ? 'checked' : '' ?>><br>

      <label for="description">Description</label>
      <textarea class="form-control text-center <?= isset($errors['nom']) ? 'red-border' : ' ' ?>" id="description" name="description"><?= $formValue['description'] ?></textarea><br>

      <div class="text-center">
        <input name="updateSubmitBtn" class="btn btn-primary" type="submit" value="Enregistrement">
      </div>
    </form>
  </div>
</div>
<br>
<div class="text-center border border-success border-2 rounded col-md-3" style="margin: 0 auto; text-align: center;">
    <a class=" navbar-brand text-dark " href="../phpexov2/affichage_annuaire.php">Afficher Annuaire</a>

</div>
<?php include_once("./includes/footer.php"); ?>