<?php
if (isset($_POST['submitdetails'])) {
    try {
        $cname = $_POST['cname'];
        $caddress = $_POST['caddress'];
        $cemail = $_POST['cemail'];
        $cphone = $_POST['cphone'];
        $cmrn = $_POST['cmrn'];
        $medicalcard = isset($_POST['medicalcard']) ? $_POST['medicalcard'] : '';

        if ($cname == '' || $caddress == '' || $cemail == '' || $cphone == '' || $cmrn == '') {
            echo "Please fill in all the fields correctly. <br>";
        } else {
            $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE PATIENTS SET Name = :cname, HomeAddress = :caddress, Email = :cemail, Phone = :cphone, MedicalCard = :medicalcard WHERE MRN = :cmrn";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':cname', $cname);
            $stmt->bindValue(':caddress', $caddress);
            $stmt->bindValue(':cemail', $cemail);
            $stmt->bindValue(':cphone', $cphone);
            $stmt->bindValue(':cmrn', $cmrn);
            $stmt->bindValue(':medicalcard', $medicalcard);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                echo "You just updated patient with MRN: " . $_POST['cmrn'] . ". Click <a href='selectupdate.php'>here</a> to go back.";
            } else {
                echo "Nothing updated.";
            }
        }
    } catch (PDOException $e) {
        $output = 'Unable to Connect to Database ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        echo $output;
    }
}
?>