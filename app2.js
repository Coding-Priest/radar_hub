$(document).ready(function () {
  $.ajax({
    url: "api/getRecommendation.php", // Replace with the actual URL of your PHP script
    dataType: "json",
    success: function (data) {
      var websiteData = data;
      var itemsPerPage = 16; // Number of items to display per page (4x4 grid)
      var currentPage = 1; // Current page number

      // Function to display the current page of website URLs and titles
      function displayPage() {
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        var pageData = websiteData.slice(startIndex, endIndex);
        var contentHtml = "";

        pageData.forEach(function (item) {
          contentHtml += '<div class="rss-box">';
          contentHtml += ' <div class="rss-title">';
          contentHtml +=
            ' <h2><a href="' +
            item.link +
            '" target="_blank" class="news_link">' +
            item.title +
            "</a></h2>";
          contentHtml +=
            ' <button class="heart-button" onclick="toggleHeart(this)"><i class="fa fa-heart"></i></button>';
          contentHtml += " </div>";
          contentHtml += "</div>";
        });

        $("#content").append(contentHtml);
      }

      // Function to update the pagination buttons
      function updatePagination() {
        var totalPages = Math.ceil(websiteData.length / itemsPerPage);
        var paginationHtml = "";

        for (var i = 1; i <= totalPages; i++) {
          paginationHtml +=
            '<button class="pagination-button' +
            (i === currentPage ? " active" : "") +
            '" data-page="' +
            i +
            '">' +
            i +
            "</button>";
        }

        $("#pagination").html(paginationHtml);
      }

      // Event listener for pagination buttons
      $(document).on("click", ".pagination-button", function () {
        currentPage = parseInt($(this).data("page"));
        displayPage();
        updatePagination();
      });

      // Initial display of the first page and pagination
      displayPage();
      updatePagination();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error retrieving website data: ", textStatus, errorThrown);
      $("#content").html(
        "<p>An error occurred while retrieving the website data.</p>"
      );
    },
  });
});

function toggleHeart(button) {
  var icon = button.querySelector("i");
  icon.classList.toggle("fa-heart-o"); // Empty heart
  icon.classList.toggle("fa-heart"); // Filled heart
  button.classList.toggle("liked");
}
