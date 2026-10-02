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
}