$(document).ready(function() {
    
    setTimeout(function() {
        $('.alert').hide();
    }, 5000);
    
    $('.question').click(function() {
        $(this).next('.answer').slideToggle();
        $(this).toggleClass('active');
    });
});