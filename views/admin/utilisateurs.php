<?php
require __DIR__ . '/../partials/header.php';
$utilisateurs = $utilisateurs ?? [];
?>

<div class="page-intro">
    <h1>Gestion des comptes</h1>
    <p><?= count($utilisateurs) ?> utilisateur(s)</p>
</div>

<table>
    <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Action</th></tr>
    <?php foreach ($utilisateurs as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['nom']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'acceptee' : 'terminee' ?>"><?= htmlspecialchars($u['role']) ?></span></td>
        <td>
            <form method="post" action="/petsitter/public/admin/utilisateurs/role" style="display:inline;">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <input type="hidden" name="role_actuel" value="<?= $u['role'] ?>">
                <button type="submit"><?= $u['role'] === 'admin' ? 'Retirer admin' : 'Passer admin' ?></button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>