<?php
    header('Content-Type: application/json');
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database = "News Recommender";
    $con = mysqli_connect($server_name, $username, $password, $database);

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    if (!$con) {
            http_response_code(404);
            die("Connection error: " . mysqli_connect_error());
    } 
    else {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];
        $password = $data['password'];

        $query = "SELECT * FROM Users WHERE Email = '$email' AND Password = '$password'";
        $result = mysqli_query($con, $query);
            
        if (mysqli_num_rows($result) > 0) {
            // User found, login successful
            http_response_code(200);
            echo json_encode(array("message" => "Login successful"));
        } else {
            // No user found, login failed
            http_response_code(401);
            echo json_encode(array("message" => "Email or password is incorrect"));
        }
    }
?>
    
