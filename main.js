// Add loading spinner animation while page is loading.
window.addEventListener("load", loadingSpinner);
function loadingSpinner() {
    document.getElementById("loadingSpinner").classList.add("hideLoadingSpinner");
    document.getElementsByTagName("body")[0].classList.remove("preload");
    setTimeout(setNavBarSection, 1000); // Ensure navBar secion updates.

    // Warn that this website is under construction.
    setTimeout( function() { showInfo("welcomeUser"); }, 3500);

    // Lazy load images in information divs.
    var infoDivs = document.getElementsByClassName("info");
    for (var i = 0; i < infoDivs.length; ++i) {
        var images = infoDivs[i].getElementsByTagName("img");
        for (var j = 0; j < images.length; ++j) {
            var image = images[j];
            image.src = image.dataset.src;
        }
    }
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
    if (navBar.classList.contains("responsive")) {
        navBar.classList.remove("responsive");
        document.getElementById("blurBackground").style.display = "none";
        navBarButton.getElementsByTagName("i")[0].classList.remove("fa-times");
        navBarButton.getElementsByTagName("i")[0].classList.add("fa-bars");
    }
    else {
        navBar.classList.add("responsive");
        document.getElementById("blurBackground").style.display = "block";
        navBarButton.getElementsByTagName("i")[0].classList.remove("fa-bars");
        navBarButton.getElementsByTagName("i")[0].classList.add("fa-times");
    }
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

setNavBarSection();
window.addEventListener("scroll", setNavBarSection);
window.addEventListener("resize", function () { setTimeout(setNavBarSection, 1000); } );


// Change size of navBar in scroll.
var navBarButton = document.getElementById("navBarButton");

function navBarResize() {
    if (document.documentElement.scrollTop > 100 || window.innerWidth <= 980) {
        navBar.style.padding = "12px 0";
        navBarButton.style.height = "55px";
        return
    }

    navBar.style.padding = "20px 0";
    navBarButton.style.height = "67px";
}

navBarResize();
window.addEventListener("scroll", navBarResize);
window.addEventListener("resize", navBarResize);


// Hide navBar when click outside.
function hideAll(event) {
    if (document.getElementById("blurBackground").contains(event.target) ||
       navBar.contains(event.target))
        closeInformation();
}

window.addEventListener("click", hideAll);


// Show information if visitor uses Google Chrome.
var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
if (isChrome && Math.random() > 0.7)
    setTimeout(function () {
        showInfo("welcomeChromeUser");
    }, 15000);


// Show information general funcion.
function showInfo(id) {
    document.getElementById(id).style.display = "block";
    document.getElementById(id).classList.add("bounceInFromRight");
    document.getElementById("blurBackground").style.display = "block";
}
