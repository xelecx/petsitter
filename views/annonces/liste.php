<?php
require __DIR__ . '/../partials/header.php';
$annonces = $annonces ?? [];
?>

<h1>Annonces disponibles</h1>

<form method="get" style="margin-bottom: 20px;">
    <label>Ville :
        <input type="text" name="ville" value="<?= htmlspecialchars($_GET['ville'] ?? '') ?>" placeholder="ex: Lyon">
    </label>

    <label>Type d'animal :
        <select name="type_animal">
            <option value="">Tous</option>
            <option value="Chien" <?= ($_GET['type_animal'] ?? '') === 'Chien' ? 'selected' : '' ?>>Chien</option>
            <option value="Chat" <?= ($_GET['type_animal'] ?? '') === 'Chat' ? 'selected' : '' ?>>Chat</option>
            <option value="Oiseau" <?= ($_GET['type_animal'] ?? '') === 'Oiseau' ? 'selected' : '' ?>>Oiseau</option>
            <option value="Autre" <?= ($_GET['type_animal'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
        </select>
    </label>

    <button type="submit">Filtrer</button>
    <a href="/petsitter/public/annonces">Reinitialiser</a>
</form>

<?php if (empty($annonces)): ?>
    <p>Aucune annonce ne correspond a ta recherche.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr><th>Titre</th><th>Ville</th><th>Animal</th><th>Dates</th><th>Action</th></tr>
        <?php foreach ($annonces as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['titre']) ?></td>
            <td><?= htmlspecialchars($a['ville']) ?></td>
            <td><?= htmlspecialchars($a['type_animal']) ?></td>
            <td><?= htmlspecialchars($a['date_debut']) ?> → <?= htmlspecialchars($a['date_fin']) ?></td>
            <td><a href="/petsitter/public/candidatures/postuler?id=<?= $a['id'] ?>">Postuler</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>