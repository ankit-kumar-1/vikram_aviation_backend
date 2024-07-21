<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'vikram_aviation';

$conn = new mysqli($server, $username, $password, $dbname);

if (mysqli_connect_error()) {
    echo json_encode(["error" => mysqli_connect_error()]);
    exit();
} else {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON data"]);
        exit();
    }

    // Use null coalescing operator to provide default values
    $title = $conn->real_escape_string($data['title'] ?? '');
    $full_name = $conn->real_escape_string($data['full_name'] ?? '');
    $company_name = $conn->real_escape_string($data['company_name'] ?? '');
    $country = $conn->real_escape_string($data['country'] ?? '');
    $contact_number = $conn->real_escape_string($data['contact_number'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $message = $conn->real_escape_string($data['message'] ?? '');

    $sql = "INSERT INTO contacts (title, full_name, company_name, country, contact_number, email, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $title, $full_name, $company_name, $country, $contact_number, $email, $message);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Success"]);
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>