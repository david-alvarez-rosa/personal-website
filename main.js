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
window.addEventListener("resize", setScrollButtonVisibility);



// Expand the navigation bar.
function toggleNavBar() {
    navBar.classList.toggle("responsive");
}


// Script for fade in animations.
var windowHeight; var windowWidth;
var elementsHidden = document.querySelectorAll(".hidden");

function getWindowDimensions() {
    windowHeight = window.innerHeight;
    windowWidth = window.innerWidth;
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
window.addEventListener("resize", setFadeInAnimations);
window.addEventListener("resize", getWindowDimensions);

getWindowDimensions();
setFadeInAnimations();


// Get nearer section to user position (so navBar can be updated).
var elementCurrent = document.querySelector(".current");
var sections = document.getElementsByTagName("section");
var navBar = document.getElementsByTagName("nav")[0];
var navBarLinks = navBar.getElementsByTagName("a");

function setNavBarSection() {
    for (var i = 0; i < sections.length; ++i) {
        var section = sections[i];
        var positionFromBottom = section.getBoundingClientRect().bottom;

        if (positionFromBottom > windowHeight / 2) {
            elementCurrent.classList.remove("current");
            elementCurrent = navBarLinks[i];
            elementCurrent.classList.add("current");
            elementCurrent.scrollIntoView();
            break;
        }
    }
}

window.addEventListener("scroll", setNavBarSection);
window.addEventListener("resize", setNavBarSection);
setNavBarSection()


// Change size of navBar in scroll.
var navBarButton = document.getElementById("navBarButton");

function navBarResize() {
    if (window.innerWidth <= 980) {
        navBar.style.padding = "0.75em 0";
        return;
    }

    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        navBar.style.padding = "0.75em 0";
        navBarButton.style.height = "3.55em";
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.05em 1em";
    }
    else {
        navBar.style.padding = "1.25em 0";
        navBarButton.style.height = "4.6em";
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.5em 1em";
    }
}

navBarResize();
window.addEventListener("scroll", navBarResize);
window.addEventListener("resize", navBarResize);


// Make navBar sticky for Medium to XSmall screens (as defined in main.css).
var sticky;

function getNavBarStickyOffset() {
    sticky = navBar.offsetTop;
    navBarButton.style.top = sticky + "px";
}

function navBarSticky() {
    if (windowWidth > 980 || windowHeight < 300) {
        navBar.classList.remove("sticky");
        return;
    }

    if (window.pageYOffset >= sticky) {
        navBar.classList.add("sticky");
        navBarButton.style.position = "fixed";
        navBarButton.style.top = "0";
    }
    else {
        navBar.classList.remove("sticky");
        navBarButton.style.position = "absolute";
        navBarButton.style.top = sticky + "px";
    }
}

navBarSticky();
getNavBarStickyOffset();
window.addEventListener("scroll", navBarSticky);
window.addEventListener("resize", navBarSticky);
window.addEventListener("resize", getNavBarStickyOffset);


// Hide navBar when click outside.
function navBarHideClick(event) {
    if (event.target.id !== "navIcon")
        navBar.classList.remove("responsive");
}

window.addEventListener("click", navBarHideClick);
