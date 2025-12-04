<?php
if (isset($_POST['submit_status'])) {
    $appid = $_POST['appid'];
    $appointment_status = $_POST['appointment_status'];
    $payment_status = $_POST['payment_status'];
    $fee = $_POST['fee'];
    
      if (!is_numeric($fee) || $fee < 0) {
        echo "<p>Invalid fee format. Please enter a valid numeric value.</p>";
        exit();
    }

    // Update appointment status, payment status, and fee in the database
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
    $stmt = $pdo->prepare("UPDATE appointment SET ArrivalStatus = ?, PaymentStatus = ?, Fees = ? WHERE AppointmentId = ?");
    $stmt->execute([$appointment_status, $payment_status, $fee, $appid]);

    echo "<p>Appointment status, payment status, and fee updated successfully.</p>";
}

?>