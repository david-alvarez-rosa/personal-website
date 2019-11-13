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
    navBar = document.getElementsByTagName("nav")[0];
    navBar.classList.toggle("responsive");
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


// Get nearer section to user position (so navBar can be updated).
var windowHeight;
var elementCurrent = document.querySelector(".current");
var sections = document.getElementsByTagName("section");
var navBar = document.getElementsByTagName("nav")[0];
var navBarLinks = navBar.getElementsByTagName("a");

function setNavBarSection() {
    for (var i = 0; i < sections.length; ++i) {
        var section = sections[i];
        var positionFromTop = section.getBoundingClientRect().top;

        if (positionFromTop - windowHeight <= -250) {
            elementCurrent.classList.remove("current");
            elementCurrent = navBarLinks[i];
            elementCurrent.classList.add("current");
            elementCurrent.scrollIntoView();
        }
    }
}

window.addEventListener("scroll", setNavBarSection);
setNavBarSection()


// Change size of navBar in scroll.
var navButton = document.getElementById("navButton");

function navBarResize() {
    if (window.innerWidth <= 980)
        return;

    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        navBar.style.padding = "0.75em 0";
        navButton.style.padding = ".85em 1.5em .5em 1.5em";
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.05em 1.5em";
    }
    else {
        navBar.style.padding = "1.25em 0";
        navButton.style.padding = "1.33em 1.5em 1.1em 1.5em";
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.5em 1.5em";
    }
}

navBarResize();
window.addEventListener("scroll", navBarResize);


// Make navBar sticky for Medium to XSmall screens (as defined in main.css).
var sticky = navBar.offsetTop;

function navBarSticky() {
    if (window.innerWidth > 980) // If width greater than 980px.
        return;

    if (window.pageYOffset >= sticky)
        navBar.classList.add("sticky");
    else
        navBar.classList.remove("sticky");
}

navBarSticky();
window.addEventListener("scroll", navBarSticky);


// Hide navBar when click outside.
function navBarHideClick(event) {
    if (event.target.id !== "navIcon")
        navBar.classList.remove("responsive");
}

window.addEventListener("click", navBarHideClick);
