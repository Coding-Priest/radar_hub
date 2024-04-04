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
    $query1 = "
        UPDATE User_Topic_Weights utw
        JOIN User_Article_Rating uar ON utw.User_ID = uar.User_ID
        JOIN Articles a ON uar.Article_ID = a.Article_ID
        SET utw.Topic_Weight = utw.Topic_Weight + uar.Article_Rating
        WHERE utw.Topic_ID = a.Topic_ID;
    ";

    $query2 = "
        UPDATE User_Type_Weights utyw
        JOIN User_Article_Rating uar ON utyw.User_ID = uar.User_ID
        JOIN Articles a ON uar.Article_ID = a.Article_ID
        SET utyw.Type_Weight = utyw.Type_Weight + uar.Article_Rating
        WHERE utyw.Type_ID = a.Type_ID;
    ";

    $query3 = "
        UPDATE User_Website_Weights uww
        JOIN User_Article_Rating uar ON uww.User_ID = uar.User_ID
        JOIN Articles a ON uar.Article_ID = a.Article_ID
        SET uww.Website_Weight = uww.Website_Weight + uar.Article_Rating
        WHERE uww.Website_ID = a.Website_ID;
    ";

    $success = true;
    $error_message = "";

    if (!mysqli_query($con, $query1)) {
        $success = false;
        $error_message .= "Error updating User_Topic_Weights: " . mysqli_error($con) . "\n";
    }

    if (!mysqli_query($con, $query2)) {
        $success = false;
        $error_message .= "Error updating User_Type_Weights: " . mysqli_error($con) . "\n";
    }

    if (!mysqli_query($con, $query3)) {
        $success = false;
        $error_message .= "Error updating User_Website_Weights: " . mysqli_error($con) . "\n";
    }

    if ($success) {
        echo json_encode(array("status" => "success", "message" => "Weights updated successfully."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error updating weights: " . $error_message));
    }
}
?>