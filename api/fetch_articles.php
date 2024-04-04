<?php
header('Content-Type: application/json');
$server_name = "localhost";
$username = "root";
$password = "";
$database = "News Recommender";
$con = mysqli_connect($server_name, $username, $password, $database);

//* RSS API call
if (!$con) {
    http_response_code(404);
    die("Connection error: " . mysqli_connect_error());
} else {
    $query = "SELECT Website_ID, Website_URL from Websites";
    $result = mysqli_query($con, $query);

    $mh = curl_multi_init();
    $channels = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $website_id = $row['Website_ID'];
        $website_url = $row['Website_URL'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $website_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $channels[$website_id] = $ch;
    }

    $active = null;
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    $article_data = [];
    foreach ($channels as $website_id => $ch) {
        $response = curl_multi_getcontent($ch);
        if ($response !== false) {
            $rss = simplexml_load_string($response);
            if ($rss !== false) {
                foreach ($rss->channel->item as $item) {
                    $article_data[] = [
                        'website_id' => $website_id,
                        'link' => (string)$item->link,
                        'title' => (string)$item->title
                    ];
                }
            }
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    echo json_encode($article_data);

    curl_multi_close($mh);

//* Making Hugging Face API call
    $url = 'https://api-inference.huggingface.co/models/MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer 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'
    ];

    $mh = curl_multi_init();
    $channels = [];

    foreach ($article_data as $article) {
        $data = [
            'inputs' => $article['title'],
            'parameters' => [
                'candidate_labels' => ['Startup', 'Robotics', 'Cyber Security', 'Web Dev', 'Block Chain', 'NLP', 'Life hacks']
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_multi_add_handle($mh, $ch);
        $channels[$article['title']] = [
            'ch' => $ch,
            'website_id' => $article['website_id'],
            'link' => $article['link']
        ];
    }

    $active = null;
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    foreach ($channels as $title => $data) {
        $ch = $data['ch'];
        $website_id = $data['website_id'];
        $link = $data['link'];

        $response = curl_multi_getcontent($ch);
        if ($response !== false) {
            $result = json_decode($response, true);
            print_r($result);
            if (isset($result['labels'])) {
                $topic = $result['labels'][0];
                $query = "SELECT Topic_ID FROM Article_Topics WHERE Topics = '$topic'";
                $result = mysqli_query($con, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    $topic_id = $row['Topic_ID'];
                    $query = "INSERT INTO Articles (Website_ID, Topic_ID, Type_ID, link, title) VALUES ($website_id, $topic_id, 1, '$link', '$title')";
                    mysqli_query($con, $query);
                    if (mysqli_query($con, $query)) {
                        // Query executed successfully
                    } else {
                        // Error occurred
                        echo "Error: " . mysqli_error($con);
                    }
                }
            }
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);
}
?>