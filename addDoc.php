<?php
include '../html/header.html';

if (isset($_POST['submitdetails'])) {
    try {
        $cname = $_POST['cname'];
        $cemail = $_POST['cemail'];
        $cphone = $_POST['cphone'];
        $cmdn = $_POST['cmdn'];


        if (strpos($cname, ' ') === false) {
            echo "Please enter the full name with a space for both forename and surname.<br>";
        }
        
        elseif (strlen($cphone) !== 12 && strlen($cphone) !== 14) {
            echo "Please enter a phone number with 12 or 14 digits.<br>";
        }
      //i got the code and logic from https://www.w3schools.com/php/php_form_url_email.asp
        elseif (!filter_var($cemail, FILTER_VALIDATE_EMAIL)) {
            echo "Please enter a valid email address.<br>";
        }
        else {
            $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM doctors WHERE MDN = :cmdn");
            $stmt->bindValue(':cmdn', $cmdn);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                echo "MDN already exists in the doctors table.<br>";
            } else {
                $sql = "INSERT INTO doctors (MDN, name, Email, phone) VALUES(:cmdn, :cname, :cemail, :cphone)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindValue(':cmdn', $cmdn);
                $stmt->bindValue(':cname', $cname);
                $stmt->bindValue(':cemail', $cemail);
                $stmt->bindValue(':cphone', $cphone);
                $stmt->execute();

                echo "Doctor added successfully. You can add another.<br>";
            }
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}

include '../html/addform.html';
include '../html/footer.html';
?>