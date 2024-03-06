<?php
function displayRSSItem($feedurl, $itemIndex){
    $feedurl = $_GET['feedurl'];
    $itemIndex = $_GET['itemIndex'];

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

$feedurl = $_GET['feedurl'];
$itemIndex = $_GET['itemIndex'];

echo displayRSSItem($feedurl, $itemIndex);
?>