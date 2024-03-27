<?php
$headlines = [
    "The 30-Day .NET Challenge, Day 7: String Built-in Methods Part 2",
    "10 Startups to Watch in 2023",
    "Robotic Arm Revolutionizes Manufacturing Industry",
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

foreach ($headlines as $headline) {
    $command = " /bin/python3.8 /opt/lampp/htdocs/radar_hub/api/zero_shot_inference_api.py " . escapeshellarg($headline);
    $classification = exec($command);
    $result = str_replace(["[ContentBlock(text='", "', type='text')]"], "", $classification);
    echo "Headline: " . $headline . "\n";
    echo "Classification: " . $result . "\n\n";
    sleep(1);
}
?>