<?php
require_once __DIR__ . '/Database.php';

class Appointment
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(
        int $appointmentId,
        int $mdn,
        int $mrn,
        string $arrivalDate,  // 'YYYY-MM-DD'
        string $arrivalTime   // 'HH:MM:SS'
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO appointment
             (AppointmentId, MDN, MRN, ArrivalDate, ArrivalTime, Fees, ArrivalStatus, PaymentStatus)
             VALUES (:id, :mdn, :mrn, :date, :time, NULL, "NA", "UNPAID")'
        );

        return $stmt->execute([
            ':id'   => $appointmentId,
            ':mdn'  => $mdn,
            ':mrn'  => $mrn,
            ':date' => $arrivalDate,
            ':time' => $arrivalTime,
        ]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM appointment WHERE AppointmentId = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByDoctorAndDate(int $mdn, string $date): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM appointment
             WHERE MDN = :mdn AND ArrivalDate = :date
             ORDER BY ArrivalTime'
        );
        $stmt->execute([
            ':mdn'  => $mdn,
            ':date' => $date,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatusAndPayment(
        int $id,
        string $status,
        string $paymentStatus,
        ?float $fee
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE appointment
             SET ArrivalStatus = :status,
                 PaymentStatus = :payment,
                 Fees = :fee
             WHERE AppointmentId = :id'
        );

        return $stmt->execute([
            ':status'  => $status,
            ':payment' => $paymentStatus,
            ':fee'     => $fee,
            ':id'      => $id,
        ]);
    }
}
