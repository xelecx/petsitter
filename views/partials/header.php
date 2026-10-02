<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetSitter</title>
    <link rel="stylesheet" href="/petsitter/public/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="/petsitter/public/annonces" class="navbar-brand">🐾 PetSitter</a>
        <div class="navbar-links">
            <a href="/petsitter/public/annonces">Annonces</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/petsitter/public/annonces/mes-annonces">Mes annonces</a>
                <a href="/petsitter/public/candidatures/mes-candidatures">Mes candidatures</a>
                <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                    <a href="/petsitter/public/admin">Admin</a>
                <?php endif; ?>
                <a href="/petsitter/public/logout" class="btn-logout">Déconnexion</a>
            <?php else: ?>
                <a href="/petsitter/public/login">Connexion</a>
                <a href="/petsitter/public/register">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">