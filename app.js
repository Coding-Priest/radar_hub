$(document).ready(
    function() {
        var feeds = [
            { url: "https://hackernoon.com/feed", itemIndex: 0 },
            { url: "https://news.ycombinator.com/rss", itemIndex: 1 },
            { url: "https://techcrunch.com/feed/", itemIndex: 4 },
            { url: "https://www.wired.com/feed/tag/ai/latest/rss", itemIndex: 5 },
            // // { url: "https://rss.app/feeds/BakkMqZJBM6z4z7T.xml", itemIndex: 2}, //Twitter AI
            // { url: "https://rss.app/feeds/1Y36svRsOf9Xbyc5.xml", itemIndex: 3 }, //HuggingFace papers
            { url: "https://hackernoon.com/feed", itemIndex: 6 },
            { url: "https://hackernoon.com/feed", itemIndex: 7 },
            { url: "https://hackernoon.com/feed", itemIndex: 8 },
            { url: "https://hackernoon.com/feed", itemIndex: 9 },
            { url: "https://hackernoon.com/feed", itemIndex: 10 },  
            { url: "https://hackernoon.com/feed", itemIndex: 11 },
            { url: "https://hackernoon.com/feed", itemIndex: 12 },
            { url: "https://hackernoon.com/feed", itemIndex: 13 },            

        ];

        feeds.forEach(
            function(feed) {
                $.get("api/readRSS.php", { feedurl: feed.url, itemIndex: feed.itemIndex, cache: new Date().getTime() + Math.random()})
                .done(function(data) {
                    $("#content").append(data);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error with AJAX request for " + feed.url + ": ", textStatus, errorThrown);
                    $("#content").append("<p>An error occurred with the feed from " + feed.url + ": " + textStatus + "</p>");
                });
            }
        );
    }
);

function toggleHeart(button) {
    var icon = button.querySelector('i');
    icon.classList.toggle('far'); // Regular heart
    icon.classList.toggle('fas'); // Solid heart
    button.classList.toggle('liked');
}
