<?php
if (isset($_POST['submitdetails'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cmdn = $_POST['cmdn'];

        // Check if the doctor has any pending appointments
        $sql_appointments = "SELECT COUNT(*) FROM appointment WHERE MDN = :cmdn AND ArrivalStatus != 'Arrived'";
        $stmt_appointments = $pdo->prepare($sql_appointments);
        $stmt_appointments->bindValue(':cmdn', $cmdn);
        $stmt_appointments->execute();
        $appointment_count = $stmt_appointments->fetchColumn();

        if ($appointment_count > 0) {
            echo "Can't delete doctor as this doctor has pending appointments.";
        } else {
            // Proceed with deleting the doctor
            $sql = 'SELECT * FROM doctors WHERE MDN = :cmdn';
            $result = $pdo->prepare($sql);
            $result->bindValue(':cmdn', $cmdn);
            $result->execute();

            while ($row = $result->fetch()) {
                $name = $row['name'];
                $email = $row['Email'];
                $id = $row['MDN'];

                echo '
                <form action="../php/deleteDoctor.php" method="post">
                    <p>' . $name . ' With Email ' . $email . ' Are you sure you want to delete this Doctor?</p>
                    <input type="hidden" name="id" value="' . $id . '">
                    <input type="submit" value="Yes, delete" name="delete">
                </form>';
            }
        }
    } catch (PDOException $e) {
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}

include '../html/deleteform.html';
include '../html/footer.html';

?>