

<?php
if (isset($_POST['submitdetails'])) {
    $selectedDate = $_POST['arrival_date'];
    $mdn = $_POST['id'];
    $cmrn = $_POST['cmrn'];

    // Validate inputs
    if (empty($selectedDate)) {
        echo "Please select an arrival date.";
    } elseif (empty($mdn) || !is_numeric($mdn) || strlen($mdn) !== 3) {
        echo "Please enter a valid 3-digit Medical Doctor Number (MDN).";
    } elseif (empty($cmrn) || !is_numeric($cmrn)||strlen($cmrn) !== 3) {
        echo "Please enter a valid 3 digit Patient Medical Record Number (MRN).";
    } else {
        // Check if MDN exists in the database
        $pdo_doctor = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
        $pdo_doctor->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt_doctor = $pdo_doctor->prepare("SELECT * FROM doctors WHERE mdn = :mdn");
        $stmt_doctor->bindParam(':mdn', $mdn, PDO::PARAM_INT);
        $stmt_doctor->execute();

        $doctor = $stmt_doctor->fetch(PDO::FETCH_ASSOC);

        if (!$doctor) {
            echo "Doctor MDN not found in doctor's file.";
        } else {
            // Check if MRN exists in the patient file
            $pdo_patient = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
            $pdo_patient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_patient = $pdo_patient->prepare("SELECT * FROM patients WHERE MRN = :cmrn");
            $stmt_patient->bindParam(':cmrn', $cmrn, PDO::PARAM_INT);
            $stmt_patient->execute();

            $patient = $stmt_patient->fetch(PDO::FETCH_ASSOC);

            if (!$patient) {
                echo "Patient MRN not found in patient file. Please enter a correct patient MRN.";
            } else {
                // Get current date and time in Ireland
                date_default_timezone_set('Europe/Dublin');
                $currentDate = date('Y-m-d'); // Format: YYYY-MM-DD
                $currentTime = date('H:i'); // Format: HH:MM (24-hour format)
        // Query the database for existing appointments for the selected doctor on the selected date
        $stmt_existing_appointments = $pdo_doctor->prepare("SELECT ArrivalTime FROM appointment WHERE MDN = ? AND ArrivalDate = ?");
        $stmt_existing_appointments->execute([$mdn, $selectedDate]);
        $existingAppointments = $stmt_existing_appointments->fetchAll(PDO::FETCH_COLUMN);

        // Set the available times based on the current time
        $startTime = strtotime('09:00'); // 9:00 AM
        $endTime = strtotime('17:00'); // 5:00 PM

        // Adjust start time if current time is after 9:00 AM on the selected date
        if ($selectedDate == $currentDate && strtotime($currentTime) >= strtotime('09:00')) {
            $startTime = strtotime('10:00'); // Start from 10:00 AM
        }

        // Calculate the end time for available times based on selected date
        $selectedEndTime = strtotime($selectedDate . ' 17:00:00');
        $endTime = min($selectedEndTime, $endTime);

        // Create an array to store available times
        $availableTimes = array();

        // Populate available times array
        for ($time = $startTime; $time <= $endTime; $time += 60 * 60) {
            $formattedTime = date('h:i A', $time);
            $formattedDateTime = $selectedDate . ' ' . $formattedTime;

            if (!in_array($formattedDateTime, $existingAppointments)) {
                if ($selectedDate > $currentDate || ($selectedDate == $currentDate && strtotime($formattedTime) > strtotime($currentTime))) {
                    $availableTimes[] = $formattedTime;
                }
            }
        }

        // Display the doctor's name, selected date, and patient's name
        echo "<h2>Doctor: " . $doctor['name'] . "</h2>";
        echo "<h2>Selected Date: " . date('d/m/Y', strtotime($selectedDate)) . "</h2>";
        echo "<h2>Patient Name: " . $patient['Name'] . "</h2>";

        // Display the combo box containing available times
        echo "<form action='make_appointment.php' method='post'>";
        echo "<input type='hidden' name='arrival_date' value='" . $_POST['arrival_date'] . "'>";
        echo "<input type='hidden' name='id' value='" . $_POST['id'] . "'>";
        echo "<input type='hidden' name='cmrn' value='" . $_POST['cmrn'] . "'>";
        echo "<input type='hidden' name='doctor_name' value='" . $doctor['name'] . "'>";
        echo "<label for='available_times'>Select Available Time:</label>";
        echo "<select name='available_times' id='available_times'>";

        foreach ($availableTimes as $time) {
            echo "<option value='$time'>$time</option>";
        }

        echo "</select>";
        echo "<br><br>";
        echo "<input type='submit' name='make_appointment' value='Make Appointment'>";
        echo "</form>";

       
    }
}
    
}}
            

include '../html/header4.html';
include '../html/makeappForm.html';
?>