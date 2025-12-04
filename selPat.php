<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
 
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>View All Patients</title>
</head>
<body>

<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow; charset=utf8', 'root', ''); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['viewPatients'])) {
        
        $sql = 'SELECT * FROM PATIENTS';
        $result = $pdo->query($sql);

        if ($result->rowCount() > 0) {
           
            echo '<table border =`1`>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Home Address</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Medical Card</th>
                        
                    </tr>';

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>
                        <td>' . $row['MRN'] . '</td>
                        <td>' . $row['Name'] . '</td>
                        <td>' . $row['HomeAddress'] . '</td>
                        <td>' . $row['Email'] . '</td>
                        <td>' . $row['Phone'] . '</td>
                         <td>' . $row['MedicalCard'] . '</td>
                      </tr>';
            }

            echo '</table>';
        } else {
            echo 'No patients found.';
        }
    }
} catch (PDOException $e) {
    $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    echo $output; 
}
?>

<form method="post">
    <input type="submit" name="viewPatients" value="View All Patients">
</form>

</body>
</html>

