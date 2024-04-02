<?php
$feedurl = "https://rss.app/feeds/QFRefKscbOdMGwaR.xml";
$rss = simplexml_load_file($feedurl);
print_r($rss);
print_r($rss->channel->item[0]->title);
print_r($rss->channel->item[0]->link);