<?php

class CandidatureController
{
    private function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /petsitter/public/login');
            exit;
        }
    }

    public function postuler(): void
    {
        $this->requireLogin();

        $idAnnonce = (int) ($_GET['id'] ?? $_POST['id_annonce'] ?? 0);
        $annonce = Annonce::findById($idAnnonce);

        if (!$annonce || $annonce['statut'] !== 'ouverte') {
            http_response_code(404);
            echo "Cette annonce n'est plus disponible.";
            return;
        }

        // Un propriétaire ne peut pas postuler à sa propre annonce
        if ((int) $annonce['id_proprietaire'] === (int) $_SESSION['user_id']) {
            http_response_code(403);
            echo "Tu ne peux pas postuler a ta propre annonce.";
            return;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = trim($_POST['message'] ?? '');

            if ($message === '') {
                $errors[] = 'Le message de motivation est obligatoire.';
            }

            // Règle métier : impossible de postuler 2 fois à la même annonce
            if (empty($errors) && Candidature::existsForGardien($idAnnonce, $_SESSION['user_id'])) {
                $errors[] = 'Tu as deja postule a cette annonce.';
            }

            if (empty($errors)) {
                Candidature::create($idAnnonce, $_SESSION['user_id'], $message);
                header('Location: /petsitter/public/candidatures/mes-candidatures');
                exit;
            }
        }

        require __DIR__ . '/../views/candidatures/postuler.php';
    }

    public function mesCandidatures(): void
    {
        $this->requireLogin();
        $candidatures = Candidature::findByGardien($_SESSION['user_id']);
        require __DIR__ . '/../views/candidatures/mes_candidatures.php';
    }
}