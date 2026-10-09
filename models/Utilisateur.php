<?php

class Utilisateur
{
    public int $id;
    public string $nom;
    public string $email;
    public string $password;
    public string $role;

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(string $nom, string $email, string $password): int
    {
        $pdo = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO utilisateurs (nom, email, password, role) VALUES (:nom, :email, :password, :role)'
        );
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => $hash,
            'role' => 'user',
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function emailExists(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }

    public static function findAll(): array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->query('SELECT * FROM utilisateurs ORDER BY date_inscription DESC');
    return $stmt->fetchAll();
}

public static function updateRole(int $id, string $role): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare('UPDATE utilisateurs SET role = :role WHERE id = :id');
    $stmt->execute(['role' => $role, 'id' => $id]);
}

public static function delete(int $id): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare('DELETE FROM utilisateurs WHERE id = :id');
    $stmt->execute(['id' => $id]);
}
}