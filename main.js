// Get the scroll back to top button.
var scrollTop = document.getElementById("scrollTop");

function setScrollButtonVisibility() {
    if (document.body.scrollTop > 750 || document.documentElement.scrollTop > 750) {
        scrollTop.style.visibility = "visible";
        scrollTop.style.opacity = 1;
    }
    else {
        scrollTop.style.visibility = "hidden";
        scrollTop.style.opacity = 0;
    }
}

window.addEventListener("scroll", setScrollButtonVisibility);


// Expand the navigation bar.
function toggleNavBar() {
    navBar = document.getElementById("navBar");
    if (navBar.className === "navBar")
        navBar.className += " responsive";
    else
        navBar.className = "navBar";
}


// Script for fade in animations.
var windowHeight;
var elementsHidden = document.querySelectorAll(".hidden");

function getWindowHeight() {
    windowHeight = window.innerHeight;
}

function setFadeInAnimations() {
    for (var i = 0; i < elementsHidden.length; ++i) {
        var elementHidden = elementsHidden[i];
        var positionFromTop = elementsHidden[i].getBoundingClientRect().top;

        if (positionFromTop - windowHeight <= -100) {
            elementHidden.classList.add("fadeIn");
            elementHidden.classList.remove("hidden");
        }
    }
}

window.addEventListener("scroll", setFadeInAnimations);
window.addEventListener("resize", getWindowHeight);

getWindowHeight();
setFadeInAnimations();


// Get nearer section to user position (so navbar can be updated).
var windowHeight;
var elementCurrent = document.querySelector(".current");
var sections = document.getElementsByTagName("section");
var navBar = document.getElementById("navBar");
var navBarLinks = navBar.getElementsByTagName("a");

function setNavBarSection() {
    for (var i = 0; i < sections.length; ++i) {
        var section = sections[i];
        var positionFromTop = section.getBoundingClientRect().top;

        if (positionFromTop - windowHeight <= -250) {
            elementCurrent.classList.remove("current");
            elementCurrent = navBarLinks[i];
            elementCurrent.classList.add("current");
        }
    }
}

window.addEventListener("scroll", setNavBarSection);
setNavBarSection()


// Change size of navbar in scroll.
var navBar = document.getElementById("navBar");

function navBarResize() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100)
        navBar.style.padding = "0.75em 0";
    else
        navBar.style.padding = "1.25em 0";
}

navBarResize();
window.addEventListener("scroll", navBarResize);
