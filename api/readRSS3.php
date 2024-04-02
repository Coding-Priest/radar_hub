<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database = "News Recommender";
$db = mysqli_connect($server_name, $username, $password, $database);

if (!$db) {
    http_response_code(404);
    die("Connection error: " . mysqli_connect_error());
} else {
    $result = $db->query("SELECT Website_ID, Website_URL FROM Websites");
    $websites = $result->fetch_all(MYSQLI_ASSOC);
    // echo "Read all the Websites";
}

$multiCurl = curl_multi_init();
$channels = [];

foreach ($websites as $website) {
    $ch = curl_init($website['Website_URL']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($multiCurl, $ch);
    $channels[$website['Website_ID']] = $ch;
}

$active = null;
do {
    $mrc = curl_multi_exec($multiCurl, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($multiCurl) != -1) {
        do {
            $mrc = curl_multi_exec($multiCurl, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

foreach ($channels as $websiteId => $ch) {
    $rssData = curl_multi_getcontent($ch);
    if ($rssData !== false) {
        $rss = simplexml_load_string($rssData);
        $items = $rss->channel->item;
        // echo "Read all the items";

        foreach ($items as $item) {
            $title = $item->title;
            $link = $item->link;
            // echo "Beginning Classification";

            // Use zero-shot classification to get the topic
            $command = "/bin/python3.8 /opt/lampp/htdocs/radar_hub/api/zero_shot_inference_api.py " . escapeshellarg($title);
            $classification = exec($command);
            $result = str_replace(["[ContentBlock(text='", "', type='text')]"], "", $classification);
            echo $result;

            // $topicName = str_replace(["[ContentBlock(text='", "', type='text')]"], "", $classification);
            // $topicId = getTopicIdByName($topicName, $db);

            // // Prepare and execute the insert query
            // $stmt = $db->prepare("INSERT INTO Articles (Website_ID, Topic_ID, Type_ID, link, title) VALUES (?, ?, ?, ?, ?)");
            // $typeId = 1; // Assuming all articles are of type 'articles'
            // $stmt->bind_param("iiiss", $websiteId, $topicId, $typeId, $link, $title);
            // $stmt->execute();
        }
    }
    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}

curl_multi_close($multiCurl);

/**
 * Function to get Topic_ID by topic name
 *
 * @param string $topicName The name of the topic
 * @param mysqli $mysqli The MySQLi database connection
 * @return int|null The Topic_ID if found, null otherwise
 */
function getTopicIdByName($topicName, $mysqli)
{
    $stmt = $mysqli->prepare("SELECT Topic_ID FROM Article_Topics WHERE Topics = ?");
    $stmt->bind_param("s", $topicName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['Topic_ID'];
    } else {
        // Handle the case where the topic does not exist
        return null;
    }
}
?>