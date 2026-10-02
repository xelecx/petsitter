<?php
require __DIR__ . '/../partials/header.php';
$stats = $stats ?? ['gardesRealisees' => 0, 'noteMoyennePlateforme' => null];
$avis = $avis ?? [];
?>

<h1>Espace Admin</h1>

<h2>Statistiques globales</h2>
<ul>
    <li>Gardes realisees : <?= (int) $stats['gardesRealisees'] ?></li>
    <li>Note moyenne de la plateforme : <?= $stats['noteMoyennePlateforme'] ?? 'aucun avis pour l\'instant' ?></li>
</ul>

<h2>Moderation des avis</h2>

<?php if (empty($avis)): ?>
    <p>Aucun avis pour l'instant.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr><th>Auteur</th><th>Cible</th><th>Note</th><th>Commentaire</th><th>Action</th></tr>
        <?php foreach ($avis as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['auteur_nom']) ?></td>
            <td><?= htmlspecialchars($a['cible_nom']) ?></td>
            <td><?= (int) $a['note'] ?></td>
            <td><?= htmlspecialchars($a['commentaire']) ?></td>
            <td>
                <form method="post" action="/petsitter/public/admin/supprimer-avis" onsubmit="return confirm('Supprimer cet avis ?');">
                    <input type="hidden" name="id_avis" value="<?= (int) $a['id'] ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>