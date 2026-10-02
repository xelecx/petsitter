<?php
require __DIR__ . '/../partials/header.php';
$errors = $errors ?? [];
$annonce = $annonce ?? ['titre' => '', 'id' => 0];
?>

<h1>Postuler : <?= htmlspecialchars($annonce['titre']) ?></h1>

<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="post">
    <label>Message de motivation :<br>
        <textarea name="message" rows="4" cols="40"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
    </label><br>
    <button type="submit">Envoyer ma candidature</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>