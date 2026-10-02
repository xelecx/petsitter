<?php

class Annonce
{
    public static function create(array $data, int $idProprietaire): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO annonces (titre, type_animal, ville, date_debut, date_fin, description, statut, id_proprietaire)
             VALUES (:titre, :type_animal, :ville, :date_debut, :date_fin, :description, :statut, :id_proprietaire)'
        );
        $stmt->execute([
            'titre' => $data['titre'],
            'type_animal' => $data['type_animal'],
            'ville' => $data['ville'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'description' => $data['description'],
            'statut' => 'ouverte',
            'id_proprietaire' => $idProprietaire,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function findByProprietaire(int $idProprietaire): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM annonces WHERE id_proprietaire = :id ORDER BY date_creation DESC'
        );
        $stmt->execute(['id' => $idProprietaire]);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM annonces WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function findOuvertes(?string $ville = null, ?string $typeAnimal = null): array
{
    $pdo = Database::getConnection();

    $sql = "SELECT * FROM annonces WHERE statut = 'ouverte'";
    $params = [];

    if (!empty($ville)) {
        $sql .= " AND ville LIKE :ville";
        $params['ville'] = '%' . $ville . '%';
    }

    if (!empty($typeAnimal)) {
        $sql .= " AND type_animal = :type_animal";
        $params['type_animal'] = $typeAnimal;
    }

    $sql .= " ORDER BY date_creation DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
    
    public static function cloturer(int $idAnnonce, int $idCandidatureRetenue): void
    {
    $pdo = Database::getConnection();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("UPDATE annonces SET statut = 'pourvue' WHERE id = :id");
        $stmt->execute(['id' => $idAnnonce]);

        Candidature::accepter($idCandidatureRetenue);
        Candidature::refuserAutres($idAnnonce, $idCandidatureRetenue);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }       
    }
    // Passe automatiquement en "terminee" toute annonce "pourvue" dont la date de fin est depassee
public static function autoCloturerExpirees(): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("UPDATE annonces SET statut = 'terminee' WHERE statut = 'pourvue' AND date_fin < CURDATE()");
    $stmt->execute();
} 

public static function countTerminees(): int
{
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM annonces WHERE statut = 'terminee'");
    return (int) $stmt->fetchColumn();
}

}                      