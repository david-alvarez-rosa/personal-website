// Add loading spinner animation while page is loading.
window.addEventListener("load", function() { setTimeout(loadingSpinner, 500); });
function loadingSpinner() {
    document.getElementById("loadingSpinner").classList.add("hideLoadingSpinner");
    document.getElementsByTagName("body")[0].classList.remove("preload");
    setTimeout(setNavBarSection, 1000); // Ensure navBar secion updates.
}


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

setScrollButtonVisibility()

window.addEventListener("scroll", setScrollButtonVisibility);
window.addEventListener("resize", setScrollButtonVisibility);


// Expand the navigation bar.
function toggleNavBar() {
    navBar.classList.toggle("responsive");
    if (document.documentElement.scrollTop <= 100)
        document.documentElement.scrollTop = 110;
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

getWindowDimensions();
setFadeInAnimations();

window.addEventListener("scroll", setFadeInAnimations);
window.addEventListener("resize", setFadeInAnimations);
window.addEventListener("resize", getWindowDimensions);


// Get nearer section to user position (so navBar can be updated).
var elementCurrent = document.querySelector(".current");
var sections = document.getElementsByTagName("section");
var navBar = document.getElementsByTagName("nav")[0];
var navBarLinks = navBar.getElementsByTagName("a");

function setNavBarSection() {
    for (var i = 0; i < sections.length; ++i) {
        var positionFromBottom = sections[i].getBoundingClientRect().bottom;

        if (positionFromBottom > windowHeight / 2) {
            elementCurrent.classList.remove("current");
            elementCurrent = navBarLinks[i];
            elementCurrent.classList.add("current");


            if (windowWidth > 980)
                elementCurrent.scrollIntoView();
            else {
                var positionFromLeft = elementCurrent.getBoundingClientRect().left;
                var positionFromRight = elementCurrent.getBoundingClientRect().right;
                var scrollStep =  positionFromRight - positionFromLeft;
                if (positionFromLeft < 0)
                    navBar.scrollLeft -= scrollStep;
                var positionFromRight = elementCurrent.getBoundingClientRect().right;
                if (positionFromRight > windowWidth - 100)
                    navBar.scrollLeft += scrollStep;
            }
            break;
        }
    }
}

setNavBarSection()
window.addEventListener("scroll", setNavBarSection);
window.addEventListener("resize", setNavBarSection);


// Change size of navBar in scroll.
var navBarButton = document.getElementById("navBarButton");

function navBarResize() {
    if (window.innerWidth <= 480)
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.05em .4em";
    else if (window.innerWidth <= 736)
        for (var i = 0; i < navBarLinks.length; ++i)
            navBarLinks[i].style.padding = "1.05em .75em";
    if (window.innerWidth <= 980) {
        navBar.style.padding = "0.75em 0";
        return;
    }

    if (document.documentElement.scrollTop > 100) {
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


// Hide navBar when click outside.
function hideAll(event) {
    console.log(event.target);
    if (document.getElementsByTagName("main")[0].contains(event.target) ||
        document.getElementsByTagName("header")[0].contains(event.target) ||
        document.getElementsByTagName("footer")[0].contains(event.target))
        closeInformation();
}

window.addEventListener("click", hideAll);


// Show information if visitor uses Google Chrome.
var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
if (isChrome)
    setTimeout(function () {
        document.getElementById("welcomeChromeUser").style.display = "block";
        document.getElementById("welcomeChromeUser").classList.add("bounceInFromRight");
        document.getElementById("blurBackground").style.display = "block";
    }, 15000);

// Warn that this website is under construction.
setTimeout(function () {
    document.getElementById("welcomeUser").style.display = "block";
    document.getElementById("welcomeUser").classList.add("bounceInFromRight");
    document.getElementById("blurBackground").style.display = "block";
}, 6000);
