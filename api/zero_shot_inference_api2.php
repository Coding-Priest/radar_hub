<?php
header('Content-Type: application/json');
$server_name = "localhost";
$username = "root";
$password = "";
$database = "News Recommender";
$con = mysqli_connect($server_name, $username, $password, $database);

//First getting the first 5 headlines of the 4 websites stored in the database
//Websites Table Website_URL attribute

//* RSS API call
if (!$con) {
    http_response_code(404);
    die("Connection error: " . mysqli_connect_error());
} else {
    $query = "SELECT Website_URL from Websites";
    $result = mysqli_query($con, $query);

    $mh = curl_multi_init();
    $channels = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $website_url = $row['Website_URL'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $website_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $channels[$website_url] = $ch;
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

    $article_count = 0;
    foreach ($channels as $website_url => $ch) {
        $response = curl_multi_getcontent($ch);
        if ($response === false) {
            echo "cURL Error: " . curl_error($ch) . "<br>";
        } else {
            $rss = simplexml_load_string($response);
            if ($rss === false) {
                echo "Failed to parse RSS feed<br>";
            } else {
                foreach ($rss->channel->item as $item) {
                    if ($article_count >= 20) {
                        break 2;
                    }
                    echo "- " . $item->title . "<br>";
                    $article_count++;
                }
            }
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);
}

//* Making Hugging Face API call
// $url = 'https://api-inference.huggingface.co/models/MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli';
// $headers = [
//     'Content-Type: application/json',
//     'Authorization: Bearer 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'
// ];

// $mh = curl_multi_init();
// $channels = [];

// foreach ($headlines as $headline) {
//     $data = [
//         'inputs' => $headline,
//         'parameters' => [
//             'candidate_labels' => ['technology', 'business', 'health', 'science']
//         ]
//     ];

//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//     curl_multi_add_handle($mh, $ch);
//     $channels[$headline] = $ch;
// }

// $active = null;
// $retries = 3;
// do {
//     $mrc = curl_multi_exec($mh, $active);
// } while ($mrc == CURLM_CALL_MULTI_PERFORM);

// while ($active && $mrc == CURLM_OK) {
//     if (curl_multi_select($mh) != -1) {
//         do {
//             $mrc = curl_multi_exec($mh, $active);
//         } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//     }
// }

// foreach ($channels as $headline => $ch) {
//     $response = curl_multi_getcontent($ch);
//     if ($response === false) {
//         $error = curl_error($ch);
//         echo "cURL Error for '$headline': " . $error . "<br>";
        
//         // Retry the failed request
//         if ($retries > 0) {
//             $retries--;
//             curl_multi_add_handle($mh, $ch);
//             continue;
//         }
//     } else {
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         if ($httpCode >= 200 && $httpCode < 300) {
//             $result = json_decode($response, true);
//             echo "Result for '$headline':<br>";
//             print_r($result);
//             echo "<br><br>";
//         } else {
//             echo "Error for '$headline': HTTP status code $httpCode<br>";
//         }
//     }
//     curl_multi_remove_handle($mh, $ch);
//     curl_close($ch);
// }

// curl_multi_close($mh);
?>