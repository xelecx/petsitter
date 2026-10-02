<?php
require __DIR__ . '/../partials/header.php';
$annonces = $annonces ?? [];
?>

<h1>Mes annonces</h1>
<a href="/petsitter/public/annonces/creer">+ Publier une annonce</a>

<table border="1" cellpadding="8">
    <tr>
        <th>Titre</th><th>Ville</th><th>Dates</th><th>Statut</th>
    </tr>
    <?php foreach ($annonces as $a): ?>
    <tr>
        <td><a href="/petsitter/public/annonces/detail?id=<?= $a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a></td>
        <td><?= htmlspecialchars($a['ville']) ?></td>
        <td><?= htmlspecialchars($a['date_debut']) ?> → <?= htmlspecialchars($a['date_fin']) ?></td>
        <td><?= htmlspecialchars($a['statut']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>