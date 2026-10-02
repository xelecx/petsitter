<?php

class AuthController
{
    public function register(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($nom === '' || $email === '' || $password === '') {
                $errors[] = 'Tous les champs sont obligatoires.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse email invalide.';
            }

            if (strlen($password) < 6) {
                $errors[] = 'Le mot de passe doit faire au moins 6 caractères.';
            }

            if (empty($errors) && Utilisateur::emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé.';
            }

            if (empty($errors)) {
                $id = Utilisateur::create($nom, $email, $password);
                $_SESSION['user_id'] = $id;
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_role'] = 'user';
                header('Location: /petsitter/public/annonces');
                exit;
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    public function login(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = Utilisateur::findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = 'Email ou mot de passe incorrect.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: /petsitter/public/annonces');
                exit;
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /petsitter/public/login');
        exit;
    }
}