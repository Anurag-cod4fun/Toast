<?php
file_put_contents("raw_data.log", file_get_contents("php://input"));

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
require_once '../config/db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve and sanitize data
  $email = $_POST['email'];
  $password = $_POST['pass'];
  $twitter = $_POST['twitter'];
  $facebook = $_POST['facebook'];
  $gplus = $_POST['gplus'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  // Check if the email is already registered
  $emailCheckStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $emailCheckStmt->execute([$email]);
  $existingUser = $emailCheckStmt->fetch(PDO::FETCH_ASSOC);

  if ($existingUser) {
    // Email already registered
    $response = ['message' => 'Email is already registered'];
    header('Content-Type: application/json');
    echo json_encode($response);
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Insert data into the users table
    $insertStmt = $conn->prepare("INSERT INTO users (email, password, twitter, facebook, gplus, fname, lname, phone, address)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->execute([$email, $hashedPassword, $twitter, $facebook, $gplus, $fname, $lname, $phone, $address]);

    // Return a success response
    $response = ['message' => 'Registration successful'];
    header('Content-Type: application/json');
    echo json_encode($response);
  }
}
?>
