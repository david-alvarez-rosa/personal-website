// Get the scroll back to top button.
var scrollTop = document.getElementById("scrollTop");

// When the user scrolls down 750px from the top of the document, show the button.
window.addEventListener("scroll", scrollFunction);

function scrollFunction() {
    if (document.body.scrollTop > 750 || document.documentElement.scrollTop > 750) {
        scrollTop.style.visibility = "visible";
        scrollTop.style.opacity = 1;
    }
    else {
        scrollTop.style.visibility = "hidden";
        scrollTop.style.opacity = 0;
    }
}


// Expand the navigation bar.
function expandNavBar() {
    navBar = document.getElementById("navBar");
    if (navBar.className === "navBar")
        navBar.className += " responsive";
    else
        navBar.className = "navBar";
}


var windowHeight;
var elementsHidden = document.querySelectorAll(".hidden");
var elementCurrent = document.querySelector(".current");
var navBar = document.getElementById("navBar");
var navBarLinks = navBar.getElementsByTagName("a");


function init() {
    windowHeight = window.innerHeight;
}

function checkPosition() {
    for (var i = 0; i < elementsHidden.length; ++i) {
        var elementHidden = elementsHidden[i];
        var positionFromTop = elementsHidden[i].getBoundingClientRect().top;

        if (positionFromTop - windowHeight <= -100) {
            elementHidden.classList.add("fadeIn");
            elementHidden.classList.remove("hidden");
        }
    }
}

window.addEventListener("scroll", checkPosition);
window.addEventListener("resize", init);

init();
checkPosition();
