$(document).ready(function() {
    
    setTimeout(function() {
        $('.alert').hide();
    }, 5000);


    function removeQueryParameters() {
        // Get the current URL
        var url = window.location.href;
        
        // Remove query parameters if present
        var newUrl = url.split('?')[0];
        
        // Update the URL without reloading the page
        window.history.replaceState(null, '', newUrl);
    }

    // Call the function to remove query parameters
    // removeQueryParameters();
});