<?php
$feedurl = "https://www.wired.com/feed/rss";
$rss = simplexml_load_file($feedurl);
$title = $rss->channel->item[0]->title;
// Saving wired logo as jpg
echo $title;
?>