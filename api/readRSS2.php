<?php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database = "News Recommender";
    $db = mysqli_connect($server_name, $username, $password, $database);

    if (!$db) {
            http_response_code(404);
            die("Connection error: " . mysqli_connect_error());
    } 
    else {
        // Fetch URLs from the database
        $result = $db->query("SELECT Website_ID, Website_URL FROM Websites");
        $websites = $result->fetch_all(MYSQLI_ASSOC);
        echo "Read all the Websites";
    }

    foreach ($websites as $website) {
        $feedUrl = $website['Website_URL'];
        $rss = simplexml_load_file($feedUrl);
        $items = $rss->channel->item;
        echo "Read all the items";

        foreach ($items as $item) {
            $title = $item->title;
            $link = $item->link;
            echo "Beginning Classification";

            //Use zero-shot classification to get the topic
            $command = " /bin/python3.8 /opt/lampp/htdocs/radar_hub/api/zero_shot_inference_api.py " . escapeshellarg($title);
            $classification = exec($command);
            echo $classification;

            $topicName = str_replace(["[ContentBlock(text='", "', type='text')]"], "", $classification);
            $topicId = getTopicIdByName($topicName, $db); // Assume getTopicIdByName fetches the Topic_ID based on the topic name

            // Prepare and execute the insert query
            $stmt = $db->prepare("INSERT INTO Articles (Website_ID, Topic_ID, Type_ID, link, title) VALUES (?, ?, ?, ?, ?)");
            $typeId = 1; // Assuming all articles are of type 'articles'
            $stmt->bind_param("iiiss", $website['Website_ID'], $topicId, $typeId, $link, $title);
            $stmt->execute();
        }
    }

    // Function to get Topic_ID by topic name
    function getTopicIdByName($topicName, $mysqli) {
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