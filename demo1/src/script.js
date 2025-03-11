document.addEventListener("DOMContentLoaded", function() {
    // searchbar
    const searchButton = document.querySelector(".search-bar button");
    if (searchButton) {
        searchButton.addEventListener("click", function() {
            alert("Search button clicked!");
        });
    }
    //signin button
    const signinButton = document.querySelector(".signin-btn");
    if (signinButton) {
        signinButton.addEventListener("click", function() {
            alert("Sign In button clicked!");
        });
    }
    //cart button
    const cartButton = document.querySelector(".cart-btn");
    if (cartButton) {
        cartButton.addEventListener("click", function() {
            alert("Cart button clicked!");
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
