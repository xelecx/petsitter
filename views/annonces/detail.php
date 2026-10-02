<?php
require __DIR__ . '/../partials/header.php';
$candidatures = $candidatures ?? [];
$annonce = $annonce ?? ['titre' => '', 'ville' => '', 'type_animal' => '', 'date_debut' => '', 'date_fin' => '', 'statut' => '', 'description' => '', 'id' => 0];
?>

<div class="page-intro">
    <h1><?= htmlspecialchars($annonce['titre']) ?></h1>
    <p><?= htmlspecialchars($annonce['ville']) ?> · <?= htmlspecialchars($annonce['type_animal']) ?> · <?= htmlspecialchars($annonce['date_debut']) ?> → <?= htmlspecialchars($annonce['date_fin']) ?>
        <span class="badge badge-<?= htmlspecialchars($annonce['statut']) ?>"><?= htmlspecialchars($annonce['statut']) ?></span>
    </p>
</div>

<p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>

<h2>Candidatures</h2>

<?php if (empty($candidatures)): ?>
    <p class="empty">Aucune candidature pour l'instant.</p>
<?php else: ?>
    <?php foreach ($candidatures as $c): ?>
        <div class="entry">
            <div class="entry-top">
                <span class="entry-name"><?= htmlspecialchars($c['gardien_nom']) ?></span>
                <span class="entry-note">note : <?= Avis::noteMoyenne((int) $c['gardien_id']) ?? 'pas encore noté' ?></span>
                <span class="badge badge-<?= htmlspecialchars($c['statut']) ?>"><?= htmlspecialchars($c['statut']) ?></span>
            </div>
            <p><?= htmlspecialchars($c['message']) ?></p>

            <?php if ($annonce['statut'] === 'ouverte' && $c['statut'] === 'en_attente'): ?>
                <form method="post" action="/petsitter/public/annonces/valider">
                    <input type="hidden" name="id_annonce" value="<?= $annonce['id'] ?>">
                    <input type="hidden" name="id_candidature" value="<?= $c['id'] ?>">
                    <button type="submit">Valider ce gardien</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($annonce['statut'] === 'terminee'): ?>
    <p style="margin-top:20px;"><a href="/petsitter/public/avis/noter?id=<?= $annonce['id'] ?>" class="btn">Laisser un avis sur cette garde</a></p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>