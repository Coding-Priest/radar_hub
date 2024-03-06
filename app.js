$(document).ready(
    function() {
        var feeds = [
            { url: "https://hackernoon.com/feed", itemIndex: 0 },
            { url: "https://hackernoon.com/feed", itemIndex: 1 },
            { url: "https://hackernoon.com/feed", itemIndex: 2 },
            { url: "https://hackernoon.com/feed", itemIndex: 3 },
            { url: "https://hackernoon.com/feed", itemIndex: 4 },
            { url: "https://hackernoon.com/feed", itemIndex: 5 },
            { url: "https://hackernoon.com/feed", itemIndex: 6 },
            { url: "https://hackernoon.com/feed", itemIndex: 7 },
        ];

        feeds.forEach(
            function(feed) {
                $.get("api/readRSS.php", { feedurl: feed.url, itemIndex: feed.itemIndex })
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
