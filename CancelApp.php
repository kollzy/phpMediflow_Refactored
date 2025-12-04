
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
        echo "<form action='../php/CancelApp.php' method='post'>";
        echo "<input type='hidden' name='appid' value='$appid'>";
        echo "<input type='submit' name='CancelAppointment' value='Cancel Appointment'>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>No appointment found with ID: $appid</p>";
    }
}


if (isset($_POST['CancelAppointment'])) {
    $appid = $_POST['appid'];

    // Delete appointment from the database
    $pdo = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
    $stmt = $pdo->prepare("DELETE FROM appointment WHERE AppointmentId = ?");
    $stmt->execute([$appid]);

    echo "<p>Appointment with ID: $appid has been canceled.</p>";
}

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
         include '../html/CancelAppForm.html';
         ?>
 