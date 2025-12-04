<?php
require_once __DIR__ . '/Database.php';

class Patient
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // Find one patient by MRN
    public function findByMrn(int $mrn): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM patients WHERE MRN = :mrn');
        $stmt->bindValue(':mrn', $mrn, PDO::PARAM_INT);
        $stmt->execute();
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        return $patient ?: null;
    }

    // Get all patients
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM patients ORDER BY Name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create patient
    public function create(int $mrn, string $name, string $email, string $address, string $phone, string $medicalCard): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO patients (MRN, Name, Email, HomeAddress, Phone, MedicalCard)
             VALUES (:mrn, :name, :email, :address, :phone, :medicalCard)'
        );

        return $stmt->execute([
            ':mrn'         => $mrn,
            ':name'        => $name,
            ':email'       => $email,
            ':address'     => $address,
            ':phone'       => $phone,
            ':medicalCard' => $medicalCard,
        ]);
    }

    // Update patient
    public function update(int $mrn, string $name, string $email, string $address, string $phone, string $medicalCard): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE patients
             SET Name = :name,
                 Email = :email,
                 HomeAddress = :address,
                 Phone = :phone,
                 MedicalCard = :medicalCard
             WHERE MRN = :mrn'
        );

        return $stmt->execute([
            ':mrn'         => $mrn,
            ':name'        => $name,
            ':email'       => $email,
            ':address'     => $address,
            ':phone'       => $phone,
            ':medicalCard' => $medicalCard,
        ]);
    }

    // Delete patient
    public function delete(int $mrn): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM patients WHERE MRN = :mrn');
        return $stmt->execute([':mrn' => $mrn]);
    }
}
