<?php
require __DIR__ . '/../partials/header.php';
$annonces = $annonces ?? [];
?>

<div class="page-intro">
    <h1>Mes annonces</h1>
    <p><?= count($annonces) ?> annonce(s) publiée(s)</p>
</div>

<p style="margin-bottom:20px;"><a href="/petsitter/public/annonces/creer" class="btn">+ Publier une annonce</a></p>

<?php if (empty($annonces)): ?>
    <p class="empty">Tu n'as encore publié aucune annonce.</p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($annonces as $a): ?>
        <div class="card">
            <div class="card-top">
                <h3 class="card-title"><a href="/petsitter/public/annonces/detail?id=<?= $a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h3>
                <span class="badge badge-<?= htmlspecialchars($a['statut']) ?>"><?= htmlspecialchars($a['statut']) ?></span>
            </div>
            <p class="card-meta"><?= htmlspecialchars($a['ville']) ?> · <?= htmlspecialchars($a['type_animal']) ?> · <?= htmlspecialchars($a['date_debut']) ?> → <?= htmlspecialchars($a['date_fin']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>