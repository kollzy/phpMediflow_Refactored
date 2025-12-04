<?php
if (isset($_POST['make_appointment'])) {
                    // Save appointment details to the database
                    $pdo_appointment = new PDO('mysql:host=localhost;dbname=mediflow;charset=utf8', 'root', '');
                    $pdo_appointment->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Generate unique appointment ID got code from https://www.w3schools.com/php/func_math_mt_rand.asp
                    $appointmentId = mt_rand(100, 999);

                    // Extract appointment details from the form
                    $arrivalDate = $_POST['arrival_date'];
                    $arrivalTime = $_POST['available_times'];
                    $mdn = $_POST['id'];
                    $cmrn = $_POST['cmrn'];

                    // Prepare and execute SQL query to insert appointment into the database
                    $stmt_insert_appointment = $pdo_appointment->prepare("INSERT INTO appointment(AppointmentId, ArrivalDate, ArrivalTime, MDN, MRN) VALUES (?, ?,  ?, ?, ?)");
                    $stmt_insert_appointment->execute([$appointmentId, $arrivalDate, $arrivalTime,  $mdn, $cmrn]);

                    // Check if appointment is successfully inserted
                    if ($stmt_insert_appointment->rowCount() > 0) {
                        echo "Appointment successfully scheduled. Your appointment ID is: " . $appointmentId;
                    } else {
                        echo "Failed to schedule appointment. Please try again.";
                    }
                }
?>