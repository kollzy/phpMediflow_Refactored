<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'DELETE FROM DOCTORS WHERE MDN = :cmdn';

    $result = $pdo->prepare($sql);
    $result->bindValue(':cmdn', $_POST['id']);
    $result->execute();

    echo "You just deleted Doctor no: " . $_POST['id'] ." \n click <a href='../html/deleteform.html'>here</a> to go back ";

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "ooops couldn't delete as that record is linked to other tables. Click <a href='deleteform.html'>here</a> to go back.";
    }
}
?>
