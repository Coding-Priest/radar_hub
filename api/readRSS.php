<?php
function displayRSSItem($feedurl, $itemIndex){
    $feedurl = $_GET['feedurl'];
    $itemIndex = $_GET['itemIndex'];

    $rss = simplexml_load_file($feedurl);
    $title = $rss->channel->item[intval($itemIndex)]->title;
    $link = $rss->channel->item[intval($itemIndex)]->link;

    $output = "<div class = 'rss-box'>";
    $output .= "<h2><a href='$link' target = '_blank' class = 'news_link'>$title</a></h2>";
    $output .= "</div>";

    return $output;
}

$feedurl = $_GET['feedurl'];
$itemIndex = $_GET['itemIndex'];

echo displayRSSItem($feedurl, $itemIndex);
?>