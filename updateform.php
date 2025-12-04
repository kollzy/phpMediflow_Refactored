<?php

include '../html/Header3.html';

try { 
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', ''); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT count(*) FROM PATIENTS WHERE MRN=:cmrn";

    $result = $pdo->prepare($sql);
    $result->bindValue(':cmrn', $_POST['id']); 
    $result->execute();

    if($result->fetchColumn() > 0) {
        $sql = 'SELECT * FROM PATIENTS WHERE MRN = :cmrn';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cmrn', $_POST['id']); 
        $result->execute();

        $row = $result->fetch();
        $id = $row['MRN'];
        $name = $row['Name'];
        $address = $row['HomeAddress'];
        $email = $row['Email'];
        $phone = $row['Phone'];
        $medicalcard = $row['MedicalCard'];
    } else {
        echo "No rows matched the query. <a href='selectupdate.php'>Click here</a> to go back.";
    }
} catch (PDOException $e) { 
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}

// Include your HTML form to update patient details here
include '../html/whotoupdate.html';


?>