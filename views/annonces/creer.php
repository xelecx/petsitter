<?php
require __DIR__ . '/../partials/header.php';
$errors = $errors ?? [];
?>

<h1>Publier une annonce</h1>

<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="post" class="panel">
    <label>Titre : <input type="text" name="titre" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>"></label><br>
    <label>Type d'animal : <input type="text" name="type_animal" value="<?= htmlspecialchars($_POST['type_animal'] ?? '') ?>"></label><br>
    <label>Ville : <input type="text" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>"></label><br>
    <label>Date début : <input type="date" name="date_debut"></label><br>
    <label>Date fin : <input type="date" name="date_fin"></label><br>
    <label>Description : <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea></label><br>
    <button type="submit">Publier</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>