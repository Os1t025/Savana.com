document.addEventListener("DOMContentLoaded", function() {
    // searchbar
    const searchButton = document.querySelector(".search-bar button");
    const searchInput = document.querySelector(".search-bar input");

    if (searchButton) {
        searchButton.addEventListener("click", function() {
            const query = searchInput.value.trim();
            
            if (query !== "") {
                window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
            } else {
                alert("Please enter a search term!");
            }
        });
    }

    // function to validate phone number
    function validatePhoneNumber() {
        var phone = document.getElementById("phone").value;
        var regex = /^\+?[0-9]*$/;
        if (!regex.test(phone)) {
            alert("Invalid phone number. Only numbers and an optional '+' are allowed.");
            return false;
        }
        return true;
    }

    const form = document.querySelector("form");
    if (form) {
        form.onsubmit = function(event) {
            if (!validatePhoneNumber()) {
                event.preventDefault();
            }
        };
    }
});


