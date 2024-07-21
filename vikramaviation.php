<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow cross-origin requests (you may want to restrict this in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Get the POST data
$data = json_decode(file_get_contents("php://input"), true);

$required_fields = ['name', 'email', 'phone', 'education', 'experience', 'why_join'];
$errors = [];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        $errors[] = ucfirst($field) . " is required.";
    }
}


$server = 'localhost';
$username = 'root';
$password ='';
$dbname = 'vikram_aviation';

$con = mysqli_connect($server, $username, $password, $dbname);

if (!$con) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

$name = mysqli_real_escape_string($con, $data['name']);
$email = mysqli_real_escape_string($con, $data['email']);
$phone = mysqli_real_escape_string($con, $data['phone']);
$education = mysqli_real_escape_string($con, $data['education']);
$experience = mysqli_real_escape_string($con, $data['experience']);
$why_join = mysqli_real_escape_string($con, $data['why_join']);


$sql = "INSERT INTO applications (name, email, phone, education, experience, why_join) VALUES ('$name', '$email', '$phone', '$education', '$experience', '$why_join')";
$result = mysqli_query($con, $sql);

if ($result) {
    http_response_code(200);
    echo json_encode(["message" => "Application submitted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Error submitting application: " . mysqli_error($con)]);
}

mysqli_close($con);
?>