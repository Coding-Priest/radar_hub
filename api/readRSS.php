<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

function displayRSSItem($feedurl, $itemIndex) {
    if (empty($feedurl) || !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        return "<p>No valid feed URL provided.</p>";
    }

    // * General links
    $rss = simplexml_load_file($feedurl);
    $title = $rss->channel->item[intval($itemIndex)]->title;
    $link = $rss->channel->item[intval($itemIndex)]->link;

    $output = "<div class = 'rss-box'>";
    $output .= "<div class = 'rss-title'>";
    $output .= "<h2><a href='$link' target = '_blank' class = 'news_link'>$title</a></h2>";
    $output .= "<button class='heart-button' onclick ='toggleHeart(this)'><i class='fa fa-heart'></i></button>";
    $output .= "</div>";
    $output .= "</div>";

    return $output;
}

$feedurl = isset($_GET['feedurl']) ? $_GET['feedurl'] : '';
$itemIndex = isset($_GET['itemIndex']) ? $_GET['itemIndex'] : 0;

echo displayRSSItem($feedurl, $itemIndex);
?>