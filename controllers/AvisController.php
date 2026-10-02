<?php

class AvisController
{
    private function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /petsitter/public/login');
            exit;
        }
    }

    public function noter(): void
    {
        $this->requireLogin();
        Annonce::autoCloturerExpirees();

        $idAnnonce = (int) ($_GET['id'] ?? $_POST['id_annonce'] ?? 0);
        $annonce = Annonce::findById($idAnnonce);

        if (!$annonce || $annonce['statut'] !== 'terminee') {
            http_response_code(403);
            echo "Cette garde n'est pas encore terminee, impossible de noter.";
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $candidatures = Candidature::findByAnnonce($idAnnonce);
        $idCible = null;

        // Determine qui je note selon mon role dans cette garde
        if ((int) $annonce['id_proprietaire'] === $userId) {
            foreach ($candidatures as $c) {
                if ($c['statut'] === 'acceptee') {
                    $idCible = (int) $c['id_gardien'];
                    break;
                }
            }
        } else {
            foreach ($candidatures as $c) {
                if ((int) $c['id_gardien'] === $userId && $c['statut'] === 'acceptee') {
                    $idCible = (int) $annonce['id_proprietaire'];
                    break;
                }
            }
        }

        if ($idCible === null) {
            http_response_code(403);
            echo "Tu n'es pas concerne par cette garde.";
            return;
        }

        if (Avis::existsForAuteur($idAnnonce, $userId)) {
            echo "Tu as deja laisse un avis pour cette garde.";
            return;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = (int) ($_POST['note'] ?? 0);
            $commentaire = trim($_POST['commentaire'] ?? '');

            if ($note < 1 || $note > 5) {
                $errors[] = 'La note doit etre entre 1 et 5.';
            }

            if (empty($errors)) {
                Avis::create($idAnnonce, $userId, $idCible, $note, $commentaire);
                header('Location: /petsitter/public/annonces/mes-annonces');
                exit;
            }
        }

        require __DIR__ . '/../views/avis/noter.php';
    }
}