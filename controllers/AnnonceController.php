<?php

class AnnonceController
{
    // Empêche l'accès si pas connecté
    private function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /petsitter/public/login');
            exit;
        }
    }

    public function creer(): void
    {
        $this->requireLogin();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titre' => trim($_POST['titre'] ?? ''),
                'type_animal' => trim($_POST['type_animal'] ?? ''),
                'ville' => trim($_POST['ville'] ?? ''),
                'date_debut' => $_POST['date_debut'] ?? '',
                'date_fin' => $_POST['date_fin'] ?? '',
                'description' => trim($_POST['description'] ?? ''),
            ];

            foreach (['titre', 'type_animal', 'ville', 'date_debut', 'date_fin'] as $champ) {
                if ($data[$champ] === '') {
                    $errors[] = "Le champ $champ est obligatoire.";
                }
            }

            if (empty($errors) && $data['date_fin'] < $data['date_debut']) {
                $errors[] = 'La date de fin doit être après la date de début.';
            }

            if (empty($errors)) {
                Annonce::create($data, $_SESSION['user_id']);
                header('Location: /petsitter/public/annonces/mes-annonces');
                exit;
            }
        }

        require __DIR__ . '/../views/annonces/creer.php';
    }

    public function mesAnnonces(): void
    {
        $this->requireLogin();
        Annonce::autoCloturerExpirees();
        $annonces = Annonce::findByProprietaire($_SESSION['user_id']);
        require __DIR__ . '/../views/annonces/mes_annonces.php';
    }

    public function liste(): void
{
    $ville = trim($_GET['ville'] ?? '');
    $typeAnimal = trim($_GET['type_animal'] ?? '');

    $annonces = Annonce::findOuvertes($ville ?: null, $typeAnimal ?: null);

    require __DIR__ . '/../views/annonces/liste.php';
}

    public function detail(): void
{
    $this->requireLogin();

    $id = (int) ($_GET['id'] ?? 0);
    $annonce = Annonce::findById($id);

    if (!$annonce || (int) $annonce['id_proprietaire'] !== (int) $_SESSION['user_id']) {
        http_response_code(403);
        echo "Acces refuse.";
        return;
    }

    $candidatures = Candidature::findByAnnonce($id);
    require __DIR__ . '/../views/annonces/detail.php';
}

public function valider(): void
{
    $this->requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /petsitter/public/annonces/mes-annonces');
        exit;
    }

    $idAnnonce = (int) ($_POST['id_annonce'] ?? 0);
    $idCandidature = (int) ($_POST['id_candidature'] ?? 0);

    $annonce = Annonce::findById($idAnnonce);
    if (!$annonce || (int) $annonce['id_proprietaire'] !== (int) $_SESSION['user_id']) {
        http_response_code(403);
        echo "Acces refuse.";
        return;
    }

    // Securite : verifie que la candidature appartient bien a cette annonce
    $candidature = Candidature::findById($idCandidature);
    if (!$candidature || (int) $candidature['id_annonce'] !== $idAnnonce) {
        http_response_code(400);
        echo "Candidature invalide.";
        return;
    }

    Annonce::cloturer($idAnnonce, $idCandidature);

    header('Location: /petsitter/public/annonces/mes-annonces');
    exit;
}
}