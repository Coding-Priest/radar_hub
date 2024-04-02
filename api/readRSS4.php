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

        foreach ($items as $item) {
            $title = $item->title;
            $link = $item->link;

            // Make the API call to Hugging Face inference API directly from PHP
            $apiUrl = "https://api-inference.huggingface.co/models/MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli";
            $headers = [
                "Authorization: Bearer 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",
                "Content-Type: application/json"
            ];
            $data = [
                "inputs" => $title,
                "parameters" => [
                    "candidate_labels" => ["Startup", "Robotics", "Cyber Security", "Web Dev", "Block Chain", "NLP", "Life hacks"]
                ]
            ];

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            curl_close($curl);

            // $result = json_decode($response, true);
            // $maxProbIndex = array_search(max($result['scores']), $result['scores']);
            // $classification = $result['labels'][$maxProbIndex];

            // echo "Classification for article '$title': $classification<br>";
            // ob_flush();
            // flush();

            // $topicName = $classification;
            // $topicId = getTopicIdByName($topicName, $db);

            // // Prepare and execute the insert query
            // $stmt = $db->prepare("INSERT INTO Articles (Website_ID, Topic_ID, Type_ID, link, title) VALUES (?, ?, ?, ?, ?)");
            // $typeId = 1; // Assuming all articles are of type 'articles'
            // $stmt->bind_param("iiiss", $websiteId, $stmt->bind_param("iiiss", $websiteId, $topicId, $typeId, $link, $title));
            // $stmt->execute();
        }
    }
    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}

curl_multi_close($multiCurl);

/**
            
            Function to get Topic_ID by topic name
            @param string $topicName The name of the topic
            @param mysqli $mysqli The MySQLi database connection
            @return int|null The Topic_ID if found, null otherwise
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
        // If the topic doesn't exist, insert it into the Article_Topics table
        $stmt = $mysqli->prepare("INSERT INTO Article_Topics (Topics) VALUES (?)");
        $stmt->bind_param("s", $topicName);
        $stmt->execute();
        return $mysqli->insert_id;
    }
}
