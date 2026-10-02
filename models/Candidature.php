<?php

class Candidature
{
    public static function create(int $idAnnonce, int $idGardien, string $message): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO candidatures (message, statut, id_annonce, id_gardien)
             VALUES (:message, :statut, :id_annonce, :id_gardien)'
        );
        $stmt->execute([
            'message' => $message,
            'statut' => 'en_attente',
            'id_annonce' => $idAnnonce,
            'id_gardien' => $idGardien,
        ]);
        return (int) $pdo->lastInsertId();
    }

    // Toutes les candidatures d'une annonce, avec le nom du gardien (jointure)
    public static function findByAnnonce(int $idAnnonce): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT c.*, u.nom AS gardien_nom, u.id AS gardien_id
             FROM candidatures c
             JOIN utilisateurs u ON u.id = c.id_gardien
             WHERE c.id_annonce = :id_annonce
             ORDER BY c.date_candidature ASC'
        );
        $stmt->execute(['id_annonce' => $idAnnonce]);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM candidatures WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function existsForGardien(int $idAnnonce, int $idGardien): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM candidatures WHERE id_annonce = :id_annonce AND id_gardien = :id_gardien'
        );
        $stmt->execute(['id_annonce' => $idAnnonce, 'id_gardien' => $idGardien]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private static function updateStatut(int $id, string $statut): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE candidatures SET statut = :statut WHERE id = :id');
        $stmt->execute(['statut' => $statut, 'id' => $id]);
    }

    public static function accepter(int $id): void
    {
        self::updateStatut($id, 'acceptee');
    }

    // Refuse toutes les candidatures d'une annonce SAUF celle retenue
    public static function refuserAutres(int $idAnnonce, int $idCandidatureRetenue): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            "UPDATE candidatures SET statut = 'refusee'
             WHERE id_annonce = :id_annonce AND id != :id_retenue"
        );
        $stmt->execute(['id_annonce' => $idAnnonce, 'id_retenue' => $idCandidatureRetenue]);
    }

    // Toutes les candidatures d'un gardien, avec le titre de l'annonce concernee (jointure)
public static function findByGardien(int $idGardien): array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        'SELECT c.*, a.titre AS annonce_titre, a.ville AS annonce_ville, a.statut AS annonce_statut
         FROM candidatures c
         JOIN annonces a ON a.id = c.id_annonce
         WHERE c.id_gardien = :id_gardien
         ORDER BY c.date_candidature DESC'
    );
    $stmt->execute(['id_gardien' => $idGardien]);
    return $stmt->fetchAll();
}
}