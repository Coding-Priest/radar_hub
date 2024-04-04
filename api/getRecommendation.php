<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "News Recommender";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the website URLs from the Recommendation_Score table for the specified user
$userId = 5; // Replace with the desired user ID
$sql = "SELECT DISTINCT link FROM Recommendation_Score rs join Articles a on a.Article_ID = rs.Article_ID WHERE User_ID = $userId ORDER BY Rec_Score DESC LIMIT 20";
$result = $conn->query($sql);

// Store the website URLs in an array
$websiteUrls = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $websiteUrls[] = $row["link"];
    }
}

// Close the database connection
$conn->close();

//Printing the website URLs
// print_r($websiteUrls);

// Return the website URLs as JSON
header('Content-Type: application/json');
echo json_encode($websiteUrls);
?>