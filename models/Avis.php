<?php

class Avis
{
    public static function create(int $idAnnonce, int $idAuteur, int $idCible, int $note, string $commentaire): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO avis (note, commentaire, id_annonce, id_auteur, id_cible)
             VALUES (:note, :commentaire, :id_annonce, :id_auteur, :id_cible)'
        );
        $stmt->execute([
            'note' => $note,
            'commentaire' => $commentaire,
            'id_annonce' => $idAnnonce,
            'id_auteur' => $idAuteur,
            'id_cible' => $idCible,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function existsForAuteur(int $idAnnonce, int $idAuteur): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM avis WHERE id_annonce = :a AND id_auteur = :u');
        $stmt->execute(['a' => $idAnnonce, 'u' => $idAuteur]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Coeur du systeme de reputation : moyenne des notes RECUES par un utilisateur
    public static function noteMoyenne(int $idUtilisateur): ?float
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT AVG(note) AS moyenne FROM avis WHERE id_cible = :id');
        $stmt->execute(['id' => $idUtilisateur]);
        $result = $stmt->fetch();
        return $result['moyenne'] !== null ? round((float) $result['moyenne'], 1) : null;
    }

    public static function nombreAvis(int $idUtilisateur): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM avis WHERE id_cible = :id');
        $stmt->execute(['id' => $idUtilisateur]);
        return (int) $stmt->fetchColumn();
    }

    // Pour l'espace admin : tous les avis avec noms auteur/cible (moderation)
    public static function findAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query(
            'SELECT av.*, ua.nom AS auteur_nom, uc.nom AS cible_nom
             FROM avis av
             JOIN utilisateurs ua ON ua.id = av.id_auteur
             JOIN utilisateurs uc ON uc.id = av.id_cible
             ORDER BY av.date DESC'
        );
        return $stmt->fetchAll();
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM avis WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function moyenneGlobale(): ?float
    {
    $pdo = Database::getConnection();
    $stmt = $pdo->query('SELECT AVG(note) AS moyenne FROM avis');
    $result = $stmt->fetch();
    return $result['moyenne'] !== null ? round((float) $result['moyenne'], 1) : null;
    }
}