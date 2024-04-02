<?php
$headlines = [
    "The 30-Day .NET Challenge, Day 7: String Built-in Methods Part 2",
    "10 Startups to Watch in 2023",
    "Sam Altman ke lawde lage hua hai",
    "Cyber Attack Targets Major Financial Institution",
    "New Web Framework Gains Popularity Among Developers",
    "Blockchain Technology Transforms Supply Chain Management",
    "Advancements in Natural Language Processing for Chatbots",
    "5 Life Hacks to Boost Your Productivity",
    "Startup Raises $10 Million in Series A Funding",
    "Robots Assist in Surgical Procedures",
    "Cybersecurity Firm Discovers New Malware Threat",
    "Web Development Trends to Watch in 2023",
    "Blockchain-based Voting System Ensures Transparency",
    "NLP Techniques Improve Sentiment Analysis Accuracy",
    "10 Life Hacks for a Healthier Lifestyle",
    "Startup Launches Innovative AI-Powered Product",
    "Robotic Drones Deliver Packages Autonomously",
    "Cyber Criminals Target Remote Workers",
    "Web Accessibility Guidelines Updated for Inclusivity",
    "Blockchain Enables Secure Cross-Border Payments"
];

$url = 'https://api-inference.huggingface.co/models/MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli';
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer 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'
];

$mh = curl_multi_init();
$channels = [];

foreach ($headlines as $headline) {
    $data = [
        'inputs' => $headline,
        'parameters' => [
            'candidate_labels' => ['technology', 'business', 'health', 'science']
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_multi_add_handle($mh, $ch);
    $channels[$headline] = $ch;
}

$active = null;
$retries = 3;
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

foreach ($channels as $headline => $ch) {
    $response = curl_multi_getcontent($ch);
    if ($response === false) {
        $error = curl_error($ch);
        echo "cURL Error for '$headline': " . $error . "<br>";
        
        // Retry the failed request
        if ($retries > 0) {
            $retries--;
            curl_multi_add_handle($mh, $ch);
            continue;
        }
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode >= 200 && $httpCode < 300) {
            $result = json_decode($response, true);
            echo "Result for '$headline':<br>";
            print_r($result);
            echo "<br><br>";
        } else {
            echo "Error for '$headline': HTTP status code $httpCode<br>";
        }
    }
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}

curl_multi_close($mh);
?>
?>