<?php
require __DIR__ . '/../partials/header.php';
$user = $user ?? ['nom' => ''];
$noteMoyenne = $noteMoyenne ?? null;
$gardesRealisees = $gardesRealisees ?? 0;
?>

<div class="page-intro">
    <h1><?= htmlspecialchars($user['nom']) ?></h1>
    <p>Profil gardien</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <span class="value"><?= $noteMoyenne ?? '—' ?></span>
        <span class="label">note moyenne</span>
    </div>
    <div class="stat-card">
        <span class="value"><?= $gardesRealisees ?></span>
        <span class="label">gardes réalisées</span>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>