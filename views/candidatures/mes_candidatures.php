<?php
require __DIR__ . '/../partials/header.php';
$candidatures = $candidatures ?? [];
?>

<h1>Mes candidatures</h1>

<?php if (empty($candidatures)): ?>
    <p>Tu n'as postule a aucune annonce pour l'instant.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr><th>Annonce</th><th>Ville</th><th>Statut candidature</th><th>Avis</th></tr>
        <?php foreach ($candidatures as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['annonce_titre']) ?></td>
            <td><?= htmlspecialchars($c['annonce_ville']) ?></td>
            <td><?= htmlspecialchars($c['statut']) ?></td>
            <td>
                <?php if ($c['annonce_statut'] === 'terminee'): ?>
                    <a href="/petsitter/public/avis/noter?id=<?= $c['id_annonce'] ?>">Laisser un avis</a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>