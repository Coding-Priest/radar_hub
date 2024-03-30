<?php
header('Content-Type: application/json');
$server_name = "localhost";
$username = "root";
$password = "";
$database = "News Recommender";
$con = mysqli_connect($server_name, $username, $password, $database);

if (!$con) {
    http_response_code(404);
    die("Connection error: " . mysqli_connect_error());
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $query = "SELECT * FROM Users WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // User found, login successful
        http_response_code(401);
        echo json_encode(array("message" => "User already exists"));
    } else {
        // No user found, Register the user
        $query = "INSERT INTO Users (Email, Password) VALUES ('$email', '$password')";
        if (mysqli_query($con, $query)) {
            http_response_code(200);
            echo json_encode(array("message" => "User registered successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to register user: " . mysqli_error($con)));
        }
    }
}
?>