<?php

class Router
{
    private array $routes = [];

    public function add(string $path, string $controller, string $method): void
    {
        $this->routes[$path] = [$controller, $method];
    }

    public function run(): void
    {
        // Récupère l'URL demandée, ex: /petsitter/annonces/creer -> annonces/creer
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Adapte le préfixe si ton projet n'est pas à la racine du serveur
        $basePath = 'petsitter/public';
        $uri = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);
        $uri = trim($uri, '/');

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Page introuvable : /$uri";
            return;
        }

        [$controllerName, $methodName] = $this->routes[$uri];

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "Méthode introuvable : $controllerName::$methodName";
            return;
        }

        $controller->$methodName();
    }
}