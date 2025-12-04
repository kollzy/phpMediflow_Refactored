
<?php
include '../html/Header3.html';

try { 
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', ''); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM PATIENTS';
    $result = $pdo->query($sql); 
?>

<b>A Quick View of Patients</b><br><br>
<table border="1">
<tr>
<th>MRN</th>
<th>Name</th>
</tr>

<?php
while ($row = $result->fetch()):
    echo '<tr><td>' . $row['MRN'] . '</td><td>'. $row['Name'] . '</td></tr>';
endwhile;
?>
</table>

<?php
} catch (PDOException $e) { 
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}

include '../html/whotoupdate.html';
?>