<?php
require __DIR__ . '/../partials/header.php';
$errors = $errors ?? [];
?>

<h1>Inscription</h1>

<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="post">
    <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"></label><br>
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></label><br>
    <label>Mot de passe : <input type="password" name="password"></label><br>
    <button type="submit">S'inscrire</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>