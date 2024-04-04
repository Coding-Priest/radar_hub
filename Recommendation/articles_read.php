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
    $query = "
    INSERT INTO Article_Read (Article_ID, User_ID, Bool_Read)
SELECT 
    m.Article_ID,
    m.User_ID,
    CASE
        WHEN m.liked = 1 OR m.clicked = 1 THEN 1
        ELSE 0
    END AS Bool_Read
FROM
    User_Article_Metrics m
ON DUPLICATE KEY UPDATE
    Bool_Read = CASE
                    WHEN m.liked = 1 OR m.clicked = 1 THEN 1
                    ELSE Bool_Read
                END;
   
    ";

    if (mysqli_query($con, $query)) {
        echo json_encode(array("status" => "success", "message" => "Articles inserted into article_read table."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error inserting articles into Recommendation_Score table: " . mysqli_error($con)));
    }
}
?>