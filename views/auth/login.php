<?php
require __DIR__ . '/../partials/header.php';
$errors = $errors ?? [];
?>

<h1>Connexion</h1>

<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="post" class="panel">
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></label><br>
    <label>Mot de passe : <input type="password" name="password"></label><br>
    <button type="submit">Se connecter</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>