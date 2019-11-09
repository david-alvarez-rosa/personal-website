// Get the scroll back to top button.
scrollTop = document.getElementById("scrollTop");

// When the user scrolls down 500px from the top of the document, show the button.
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
        scrollTop.style.visibility = "visible";
        scrollTop.style.opacity = 1;
    }
    else {
        scrollTop.style.visibility = "hidden";
        scrollTop.style.opacity = 0;
    }
}
