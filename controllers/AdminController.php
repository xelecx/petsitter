<?php

class AdminController
{
    private function requireAdmin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /petsitter/public/login');
            exit;
        }
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "Acces reserve aux administrateurs.";
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats = [
            'gardesRealisees' => Annonce::countTerminees(),
            'noteMoyennePlateforme' => Avis::moyenneGlobale(),
        ];

        $avis = Avis::findAll();

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function supprimerAvis(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id_avis'] ?? 0);
            Avis::delete($id);
        }

        header('Location: /petsitter/public/admin');
        exit;
    }

    public function utilisateurs(): void
{
    $this->requireAdmin();
    $utilisateurs = Utilisateur::findAll();
    require __DIR__ . '/../views/admin/utilisateurs.php';
}

public function toggleRole(): void
{
    $this->requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $nouveauRole = ($_POST['role_actuel'] ?? 'user') === 'admin' ? 'user' : 'admin';
        Utilisateur::updateRole($id, $nouveauRole);
    }

    header('Location: /petsitter/public/admin/utilisateurs');
    exit;
}
} 