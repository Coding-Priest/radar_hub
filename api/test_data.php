<?php

// * HackerNoon
// $feedurl = "https://www.hackernoon.com/feed";
// $rss = simplexml_load_file($feedurl);
// $title = $rss->channel->item[0]->title;
// $link = $rss->channel->item[0]->link;
// $image = $rss->channel->item[0]->enclosure['url'];
// $description = $rss->channel->item[0]->description;
// print($description);
// print_r($link);
// print_r($title);


// ! Google Research Blog (Issues)
// $feedurl = "https://research.googleblog.com/feeds/posts/default";
// $rss = simplexml_load_file($feedurl);
// print_r($rss -> entry[0] ->link['href']);
// print_r($rss -> entry[0] ->title[0]);

// foreach ($rss->entry as $entry) {
//     $title = (string) $entry->title;
//     $link = (string) $entry->link['href'];
    
//     echo "Title: $title\n";
//     echo "Link: $link\n";
//     echo "\n";
// }

// * Hacker News
// $feedurl = "https://news.ycombinator.com/rss";
// $xml = file_get_contents($feedurl);
// if ($xml === false) {
//     echo "Failed to fetch RSS feed.";
// } else {
//     $rss = simplexml_load_string($xml);
//     if ($rss === false) {
//         echo "Failed to parse RSS feed.";
//     } else {
//         print_r($rss);
//     }
// }

// * Twitter AI List
$feedurl = "https://rss.app/feeds/QFRefKscbOdMGwaR.xml";
$rss = simplexml_load_file($feedurl);
print_r($rss);
print_r($rss->channel->item[0]->title);
print_r($rss->channel->item[0]->link);

?>