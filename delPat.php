<?php
if (isset($_POST['submitdetails'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT count(*) FROM PATIENTS WHERE MRN = :cmrn';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cmrn', $_POST['cmrn']);
        $result->execute();

        if ($result->fetchColumn() > 0) {
            $sql = 'SELECT * FROM PATIENTS WHERE MRN = :cmrn';
            $result = $pdo->prepare($sql);
            $result->bindValue(':cmrn', $_POST['cmrn']);
            $result->execute();

            while ($row = $result->fetch()) {
                echo $row['Name'] . ' ' . $row['Email'] . ' Are you sure you want to delete ??
                <form action="../php/delPat.php" method="post">
                    <input type="hidden" name="id" value="' . $row['MRN'] . '">
                    <input type="submit" value="yes delete" name="delete">
                </form>';
            }
        } else {
            echo "No rows matched the query.";
        }
    } catch (PDOException $e) {
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}

if (isset($_POST['delete'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointment WHERE MRN = :mrn AND ArrivalStatus != 'Arrived'");
        $stmt->bindValue(':mrn', $_POST['id']);
        $stmt->execute();
        $pendingAppointments = $stmt->fetchColumn();

        if ($pendingAppointments > 0) {
            echo "Cannot delete patient. There are pending appointments associated with this patient.";
        } else {
            $delStmt = $pdo->prepare("DELETE FROM PATIENTS WHERE MRN = :id");
            $delStmt->bindValue(':id', $_POST['id']);
            $delStmt->execute();
            echo "Patient record deleted successfully.";
        }
    } catch (PDOException $e) {
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}

include '../html/deleteform2.html';
?>

 