<?php
require_once __DIR__ . '/Database.php';

class Doctor
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance(); 
    }

    public function findByMdn(int $mdn): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM doctors WHERE MDN = :mdn');
        $stmt->bindValue(':mdn', $mdn, PDO::PARAM_INT);
        $stmt->execute();

        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
        return $doctor ?: null;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM doctors ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $mdn, string $name, string $email, string $phone): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO doctors (MDN, name, Email, phone) VALUES (:mdn, :name, :email, :phone)'
        );
        return $stmt->execute([
            ':mdn'   => $mdn,
            ':name'  => $name,
            ':email' => $email,
            ':phone' => $phone,
        ]);
    }

    public function delete(int $mdn): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM doctors WHERE MDN = :mdn');
        return $stmt->execute([':mdn' => $mdn]);
    }
}
