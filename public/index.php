<?php

session_start();

require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Router.php';

// Charge tous les contrôleurs et modèles automatiquement
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../controllers/{$class}.php",
        __DIR__ . "/../models/{$class}.php",
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$router = new Router();

// Déclaration des routes : URL => [Controller, méthode]
$router->add('', 'AnnonceController', 'liste');
$router->add('annonces', 'AnnonceController', 'liste');
$router->add('annonces/creer', 'AnnonceController', 'creer');
$router->add('annonces/mes-annonces', 'AnnonceController', 'mesAnnonces');
$router->add('annonces/detail', 'AnnonceController', 'detail');
$router->add('annonces/valider', 'AnnonceController', 'valider');

$router->add('candidatures/postuler', 'CandidatureController', 'postuler');
$router->add('candidatures/mes-candidatures', 'CandidatureController', 'mesCandidatures');
$router->add('candidatures/postuler', 'CandidatureController', 'postuler');
$router->add('candidatures/mes-candidatures', 'CandidatureController', 'mesCandidatures');

$router->add('avis/noter', 'AvisController', 'noter');

$router->add('login', 'AuthController', 'login');
$router->add('register', 'AuthController', 'register');
$router->add('logout', 'AuthController', 'logout');

$router->add('avis/noter', 'AvisController', 'noter');

$router->add('admin', 'AdminController', 'dashboard');
$router->add('admin/supprimer-avis', 'AdminController', 'supprimerAvis');

$router->run();