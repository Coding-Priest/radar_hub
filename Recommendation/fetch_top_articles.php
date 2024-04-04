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
    INSERT INTO Recommendation_Score (Recommendation_ID, User_ID, Article_ID, Rec_Score)
    SELECT 
    (SELECT COALESCE(MAX(Recommendation_ID), 0) FROM Recommendation_Score) + ROW_NUMBER() OVER (ORDER BY u.User_ID, a.Article_ID) AS Recommendation_ID,
        u.User_ID,
        a.Article_ID,
        COALESCE(uww.Website_Weight, 0) + COALESCE(utw.Type_Weight, 0) + COALESCE(utow.Topic_Weight, 0) AS Rec_Score
    FROM 
        Articles a
        CROSS JOIN Users u
        LEFT JOIN User_Website_Weights uww ON a.Website_ID = uww.Website_ID AND u.User_ID = uww.User_ID
        LEFT JOIN User_Type_Weights utw ON a.Type_ID = utw.Type_ID AND u.User_ID = utw.User_ID
        LEFT JOIN User_Topic_Weights utow ON a.Topic_ID = utow.Topic_ID AND u.User_ID = utow.User_ID
    WHERE
        (u.User_ID, a.Article_ID) IN (
            SELECT 
                User_ID,
                Article_ID
            FROM (
                SELECT 
                    u.User_ID,
                    a.Article_ID,
                    COALESCE(uww.Website_Weight, 0) + COALESCE(utw.Type_Weight, 0) + COALESCE(utow.Topic_Weight, 0) AS Rec_Score,
                    ROW_NUMBER() OVER (PARTITION BY u.User_ID ORDER BY COALESCE(uww.Website_Weight, 0) + COALESCE(utw.Type_Weight, 0) + COALESCE(utow.Topic_Weight, 0) DESC) AS RowNum
                FROM 
                    Articles a
                    CROSS JOIN Users u
                    LEFT JOIN User_Website_Weights uww ON a.Website_ID = uww.Website_ID AND u.User_ID = uww.User_ID
                    LEFT JOIN User_Type_Weights utw ON a.Type_ID = utw.Type_ID AND u.User_ID = utw.User_ID
                    LEFT JOIN User_Topic_Weights utow ON a.Topic_ID = utow.Topic_ID AND u.User_ID = utow.User_ID
            ) t
            WHERE t.RowNum <= 20
        )
    ORDER BY 
        u.User_ID,
        Rec_Score DESC;
    ";

    if (mysqli_query($con, $query)) {
        echo json_encode(array("status" => "success", "message" => "Articles inserted into Recommendation_Score table."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error inserting articles into Recommendation_Score table: " . mysqli_error($con)));
    }
}
?>