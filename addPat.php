<?php
include '../html/header2.html';

if (isset($_POST['submitdetails'])) {
    try {
        $cname = $_POST['cname'];
        $caddress = $_POST['caddress'];
        $cemail = $_POST['cemail'];
        $cphone = $_POST['cphone'];
        $cmrn = $_POST['cmrn'];
        $medicalcard = isset($_POST['medicalcard']) ? $_POST['medicalcard'] : '';

        if (strpos($cname, ' ') === false) {
            echo "Please enter the full name with a space for both forename and surname.<br>";
        } 
        //i got the empty function logic gotten from https://www.w3schools.com/php/func_var_empty.asp#:~:text=The%20empty()%20function%20checks,0
        elseif (empty($caddress)) {
            echo "Please enter the home address.<br>";
        }
        elseif (!filter_var($cemail, FILTER_VALIDATE_EMAIL)) {
            echo "Please enter a valid email address.<br>";
        }
        elseif (strlen($cphone) !== 10 && strlen($cphone) !== 12) {
            echo "Please enter a phone number with either 10 or 12 digits.<br>";
        }
        elseif (empty($cmrn)) {
            echo "Please enter the Medical Record Number (MRN).<br>";
        }
        else {
            $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM PATIENTS WHERE MRN = :cmrn");
            $stmt->bindValue(':cmrn', $cmrn);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "MRN already exists in the patients table.<br>";
            } else {
                $sql = "INSERT INTO PATIENTS (Name, HomeAddress, Email, Phone, MRN, MedicalCard) VALUES(:cname, :caddress, :cemail, :cphone, :cmrn, :medicalcard)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindValue(':cname', $cname);
                $stmt->bindValue(':caddress', $caddress);
                $stmt->bindValue(':cemail', $cemail);
                $stmt->bindValue(':cphone', $cphone);
                $stmt->bindValue(':cmrn', $cmrn);
                $stmt->bindValue(':medicalcard', $medicalcard);
                $stmt->execute();

                echo "Patient added successfully. You can add another.<br>";
            }
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}

include '../html/addform2.html';
include '../html/footer.html';
?>
