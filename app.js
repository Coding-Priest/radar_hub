$(document).ready(
    function(){
        $.get("api/readRSS.php", { feedurl: "https://www.theguardian.com/uk/rss", itemIndex: 0 })

        .done(function(data){
            $("#content").append(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            console.error("Error with AJAX request: ", textStatus, errorThrown);
            // You could also update the #content div to display an error message to the user
            $("#content").html("An error occurred: " + textStatus);
        });
    }
);
