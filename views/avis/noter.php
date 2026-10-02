<?php
require __DIR__ . '/../partials/header.php';
$errors = $errors ?? [];
$idAnnonce = $idAnnonce ?? 0;
?>

<h1>Laisser un avis</h1>

<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="post" class="panel">
    <input type="hidden" name="id_annonce" value="<?= (int) $idAnnonce ?>">

    <p>Note :
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <label><input type="radio" name="note" value="<?= $i ?>"> <?= $i ?></label>
        <?php endfor; ?>
    </p>

    <label>Commentaire :<br>
        <textarea name="commentaire" rows="3" cols="40"></textarea>
    </label><br>

    <button type="submit">Envoyer mon avis</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>