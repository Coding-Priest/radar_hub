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

// Retrieve the website URLs and titles from the Recommendation_Score table for the specified user
$userId = 5; // Replace with the desired user ID
$sql = "SELECT DISTINCT a.link, a.title FROM Recommendation_Score rs JOIN Articles a ON a.Article_ID = rs.Article_ID WHERE User_ID = $userId ORDER BY Rec_Score DESC LIMIT 20";
$result = $conn->query($sql);

// Store the website URLs and titles in an array
$websiteData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $websiteData[] = array(
            'link' => $row["link"],
            'title' => $row["title"]
        );
    }
}

// Close the database connection
$conn->close();

// Return the website data as JSON
header('Content-Type: application/json');
echo json_encode($websiteData);
?>