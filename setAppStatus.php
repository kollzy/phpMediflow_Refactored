<?php
if (isset($_POST['submitappid'])) {
    $appid = $_POST['appid'];

    // Check if appointment exists and retrieve details
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
    $stmt = $pdo->prepare("SELECT * FROM appointment WHERE AppointmentId = ?");
    $stmt->execute([$appid]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        echo "<div class='appointment-details'>";
        echo "<p><strong>Appointment ID:</strong> {$appointment['AppointmentId']}</p>";
        echo "<p><strong>MDN:</strong> {$appointment['MDN']}</p>";
        echo "<p><strong>MRN:</strong> {$appointment['MRN']}</p>";
        echo "<p><strong>Arrival Date:</strong> {$appointment['ArrivalDate']}</p>";
        echo "<p><strong>Arrival Time:</strong> {$appointment['ArrivalTime']}</p>";

        // Display the form to set appointment status, payment status, and fee
        echo "<form action='processStatus.php' method='post'>";
        echo "<input type='hidden' name='appid' value='$appid'>";

        echo "<label for='appointment_status'>Appointment Status:</label>";
        echo "<select name='appointment_status' id='appointment_status'>";
        echo "<option value='arrived'>Arrived</option>";
        echo "</select>";
        echo "<br><br>";
        echo "<label for='payment_status'>Payment Status:</label>";
        echo "<select name='payment_status' id='payment_status'>";
        echo "<option value='PATIENT_PAID'>Patient Paid</option>";
        echo "<option value='COVERED_BY_MEDICALCARD' selected>Covered by Medical Card</option>";
        echo "</select>";
        echo "<br><br>";
        echo "<label for='fee'>Fee:</label>";
        echo "<input type='number' id='fee' name='fee'>";
        echo "<br><br>";
        echo "<input type='submit' name='submit_status' value='Set Status'>";
        echo "</form>";

        echo "</div>";
    } else {
        echo "<p>No appointment found with ID: $appid</p>";
    }
}
include '../html/setAppStatusForm.html';
?>



       