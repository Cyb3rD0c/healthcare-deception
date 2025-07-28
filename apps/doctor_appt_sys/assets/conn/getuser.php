<?php
include_once 'dbconnect.php';

$date = $_GET['q'];

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully<br>";

// Debugging output
echo "Date received: " . htmlspecialchars($date) . "<br>";

// SQL query to fetch doctor schedule based on date
$query = "SELECT * FROM doctorschedule WHERE scheduleDate = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "Doctor: " . $row["doctorName"] . " - Time: " . $row["startTime"] . "<br>";
    }
} else {
    echo "Doctor is not available at the moment. Please try again later.";
}

$stmt->close();
$conn->close();
?>
