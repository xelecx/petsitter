<?php
require __DIR__ . '/../partials/header.php';
$annonces = $annonces ?? [];
?>

<div class="page-intro">
    <h1>Annonces disponibles</h1>
    <p>Trouve une garde près de chez toi</p>
</div>

<form method="get" class="filter-bar">
    <div>
        <label>Ville</label>
        <input type="text" name="ville" value="<?= htmlspecialchars($_GET['ville'] ?? '') ?>" placeholder="ex: Lyon">
    </div>
    <div>
        <label>Type d'animal</label>
        <select name="type_animal">
            <option value="">Tous</option>
            <option value="Chien" <?= ($_GET['type_animal'] ?? '') === 'Chien' ? 'selected' : '' ?>>Chien</option>
            <option value="Chat" <?= ($_GET['type_animal'] ?? '') === 'Chat' ? 'selected' : '' ?>>Chat</option>
            <option value="Oiseau" <?= ($_GET['type_animal'] ?? '') === 'Oiseau' ? 'selected' : '' ?>>Oiseau</option>
            <option value="Autre" <?= ($_GET['type_animal'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
        </select>
    </div>
    <button type="submit">Filtrer</button>
    <a href="/petsitter/public/annonces" class="btn-ghost btn" style="display:inline-block;">Réinitialiser</a>
</form>

<?php if (empty($annonces)): ?>
    <p class="empty">Aucune annonce ne correspond à ta recherche.</p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($annonces as $a): ?>
        <div class="card">
            <div class="card-top">
                <h3 class="card-title"><?= htmlspecialchars($a['titre']) ?></h3>
            </div>
            <p class="card-meta"><?= htmlspecialchars($a['ville']) ?> · <?= htmlspecialchars($a['type_animal']) ?> · <?= htmlspecialchars($a['date_debut']) ?> → <?= htmlspecialchars($a['date_fin']) ?></p>
            <div class="card-footer">
                <a href="/petsitter/public/candidatures/postuler?id=<?= $a['id'] ?>" class="btn">Postuler</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>