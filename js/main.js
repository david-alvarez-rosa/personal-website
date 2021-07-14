/**
 *
 * @source: https://david.alvarezrosa.com/js/main.js
 *
 * @licstart  The following is the entire license notice for the
 *  JavaScript code in this page.
 *
 * Copyright (C) 2019-2021  David \'Alvarez Rosa
 *
 *
 * The JavaScript code in this page is free software: you can
 * redistribute it and/or modify it under the terms of the GNU
 * General Public License (GNU GPL) as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option)
 * any later version.  The code is distributed WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.
 *
 * As additional permission under GNU GPL version 3 section 7, you
 * may distribute non-source (e.g., minimized or compacted) forms of
 * that code without the copy of the GNU GPL normally required by
 * section 4, provided you include this license notice and a URL
 * through which recipients can access the Corresponding Source.
 *
 * @licend  The above is the entire license notice
 * for the JavaScript code in this page.
 *
 */


// Add loading spinner animation while page is loading.
window.addEventListener("load", loadingSpinner);
function loadingSpinner() {
    document.getElementById("loadingSpinner").classList.add("hideLoadingSpinner");
    document.getElementsByTagName("body")[0].classList.remove("preload");
    setTimeout(setNavBarSection, 1000); // Ensure navBar secion updates.

    // Load font awesome.
    setTimeout(function () {
        var fontawesome = document.getElementById("fontawesome");
        fontawesome.href = fontawesome.dataset.href;
    }, 2550);

    // // Warn that this website is under construction.
    // setTimeout( function() { showInfo("welcomeUser"); }, 3500);
}


// Automatically update the background image.
var imageIterator = 0;
var imageIteratorNext = 1;
var header = document.getElementsByTagName("header")[0];
var headerImages = header.getElementsByClassName("backgroundImage");
var footer = document.getElementsByTagName("footer")[0];
var footerImages = footer.getElementsByClassName("backgroundImage");


function randomShuffle(array) {
    var tmp, current, top = array.length;
    if (top) while (--top) {
        current = Math.floor(Math.random() * (top + 1));
        tmp = array[current];
        array[current] = array[top];
        array[top] = tmp;
    }
    return array;
}

var imagesOrder = [];
for (var i = 0; i < headerImages.length; ++i)
    imagesOrder.push(i);
imagesOrder = randomShuffle(imagesOrder);
var animationDuration = 18000; // In miliseconds.
var reverse = "";
var animationStop = false;
var animationTimeout, animationTimeoutAux;

function updateBackgroundIterators() {
    imageIterator = imageIteratorNext;
    if (imageIteratorNext == headerImages.length - 1)
        imageIteratorNext = 0
    else
        ++imageIteratorNext;
}

function updateBackgroundImage() {
    clearTimeout(animationTimeoutAux);

    if (animationStop)
        return;

    var currentHeaderImage = headerImages[imagesOrder[imageIterator]];
    var currentFooterImage = footerImages[imagesOrder[imageIterator]];
    currentHeaderImage.classList.remove("imageIn", "imageInReverse", "backgroundImageFirst");
    currentFooterImage.classList.remove("imageIn", "imageInReverse", "backgroundImageFirst");
    currentHeaderImage.classList.add("imageOut" + reverse);
    currentFooterImage.classList.add("imageOut" + reverse);

    var nextHeaderImage = headerImages[imagesOrder[imageIteratorNext]];
    var nextFooterImage = footerImages[imagesOrder[imageIteratorNext]];
    nextHeaderImage.classList.add("imageIn" + reverse);
    nextFooterImage.classList.add("imageIn" + reverse);
    nextHeaderImage.classList.remove("imageOut", "imageOutReverse");
    nextFooterImage.classList.remove("imageOut", "imageOutReverse");

    animationTimeout = setTimeout(function() {
        nextHeaderImage.classList.remove("imageIn", "imageInReverse");
        nextFooterImage.classList.remove("imageIn", "imageInReverse");
    }, animationDuration);

    setTimeout(function() {
        document.documentElement.style.setProperty("--main-color",
                                                   nextHeaderImage.dataset.color);
        updateBackgroundIterators();
    }, 500);

    animationTimeoutAux = setTimeout( function() {
        updateBackgroundImage();
    }, animationDuration);
}

var headerFirstImage = headerImages[imagesOrder[imageIterator]];
var footerFirstImage = footerImages[imagesOrder[imageIterator]];
headerFirstImage.classList.add("backgroundImageFirst");
footerFirstImage.classList.add("backgroundImageFirst");
document.documentElement.style.setProperty("--main-color",
                                           headerFirstImage.dataset.color);
setTimeout(updateBackgroundImage, animationDuration);


// Lazy loading of images.
function lazyLoadImages() {
    // Load images in information divs.
    var infoDivs = document.getElementsByClassName("info");
    for (var i = 0; i < infoDivs.length; ++i) {
        var infoImages = infoDivs[i].getElementsByTagName("img");
        for (var j = 0; j < infoImages.length; ++j) {
            var image = infoImages[j];
            image.src = image.dataset.src;
        }
    }
    // Load background images.
    for (var i = 0; i < headerImages.length; ++i) {
        var image = headerImages[imagesOrder[i]];
        image.src = image.dataset.src;
        image = footerImages[imagesOrder[i]];
        image.src = image.dataset.src;
    }
}

window.addEventListener("load", lazyLoadImages);


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

setScrollButtonVisibility();

window.addEventListener("scroll", setScrollButtonVisibility);
window.addEventListener("resize", setScrollButtonVisibility);


// Expand the navigation bar.
var navBar = document.getElementById("navBar");
var navBarExpanded = document.getElementById("navBarExpanded");
navBarExpanded.style.display = "none";
var mainDiv = document.getElementsByTagName("main")[0];
var licenseDiv = document.getElementById("license");

function toggleNavBar() {
    if (navBarExpanded.style.display === "none") {
        navBarExpanded.style.display = "block";
        navBar.style.display = "none";
        mainDiv.classList.add("mainShrunken");
        licenseDiv.classList.add("licenseShrunken");
    }
    else {
        navBarExpanded.style.display = "none";
        navBar.style.display = "block";
        mainDiv.classList.remove("mainShrunken");
        licenseDiv.classList.remove("licenseShrunken");
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
var elementCurrent = document.getElementsByClassName("current")[0];
var elementCurrentExpanded = document.getElementsByClassName("current")[1];
var sections = document.getElementsByTagName("section");
var navBar = document.getElementById("navBar");
var navBarDiv = document.getElementById("navBarDiv");
var navBarLinks = navBar.getElementsByTagName("a");
var navBarExpanded = document.getElementById("navBarExpanded");
var navBarExpandedLinks = navBarExpanded.getElementsByTagName("a");
var scrollNavBarTimeout;
var scrollNavBarTime = 0;
var links = document.getElementsByTagName("a");
for (var i = 0; i < links.length; ++i) {
    var link = links[i];
    if (link.href.includes("#"))
        link.onclick = function() { scrollNavBarTime = 1525; };
}

function scrollNavBarToView() {
    // Scroll navigation bar to show current section.
    var positionFromRight = elementCurrent.getBoundingClientRect().right;
    var positionFromLeft = elementCurrent.getBoundingClientRect().left;
    var scrollStep =  positionFromRight - positionFromLeft;
    if (windowWidth > 980) {
        positionFromLeft = elementCurrent.getBoundingClientRect().left -
            document.getElementsByTagName("header")[0].getBoundingClientRect().width;
    }
    if (positionFromLeft < 0)
        navBarDiv.scrollLeft -= scrollStep;

    if (positionFromRight > windowWidth - 100)
        navBarDiv.scrollLeft += scrollStep;

    // Scroll expanded navigation bar to show current section.
    var positionFromBottom = elementCurrentExpanded.getBoundingClientRect().bottom;
    if (positionFromBottom > windowHeight - 15)
        navBarExpanded.scrollTop += windowHeight / 6;
    var positionFromTop = elementCurrentExpanded.getBoundingClientRect().top;
    if (positionFromTop < 100)
        navBarExpanded.scrollTop = 0;
}

function setNavBarSection() {
    for (var i = 0; i < sections.length; ++i) {
        var positionFromBottom = sections[i].getBoundingClientRect().bottom;

        if (positionFromBottom > windowHeight / 2) {
            elementCurrent.classList.remove("current");
            elementCurrent = navBarLinks[i];
            elementCurrent.classList.add("current");

            elementCurrentExpanded.classList.remove("current");
            elementCurrentExpanded = navBarExpandedLinks[i];
            elementCurrentExpanded.classList.add("current");

            scrollNavBarTimeout = setTimeout(scrollNavBarToView,
                                             scrollNavBarTime);
            scrollnavBarTime = 0;
            break;
        }
    }
}

setNavBarSection();
window.addEventListener("scroll", setNavBarSection);
window.addEventListener("resize", function () { setTimeout(setNavBarSection, 1000); } );


// Change size of navBar in scroll.
var navBarDiv = document.getElementById("navBarDiv");
var navBarButton = document.getElementById("navBarButton");

function navBarResize() {
    if ((window.innerWidth > 980 && document.documentElement.scrollTop > 100) ||
        (window.innerWidth <= 980 &&
         document.documentElement.scrollTop > header.clientHeight)) {
        navBarDiv.style.padding = "12px 0";
        navBarDiv.style.height = "54px";
        navBarButton.style.height = "51px";
        return;
    }

    navBarDiv.style.padding = "20px 0";
    navBarDiv.style.height = "70px";
    navBarButton.style.height = "67px";
}

navBarResize();
window.addEventListener("scroll", navBarResize);
window.addEventListener("resize", navBarResize);


// Hide navBar when click outside.
function hideAll(event) {
    if (windowWidth <= 480 &&
        document.getElementById("navBarExpanded").contains(event.target))
        closeInformation();

    if (document.getElementById("blurBackground").contains(event.target))
        closeInformation();
}

window.addEventListener("click", hideAll);


// // Show information if visitor uses Google Chrome.
// var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
// if (isChrome && Math.random() > 0.7)
//     setTimeout(function () {
//         showInfo("welcomeChromeUser");
//     }, 15000);


// Show information general funcion.
function showInfo(id) {
    document.getElementById(id).style.display = "block";
    document.getElementById(id).classList.add("bounceInFromRight");
    document.getElementById("blurBackground").style.display = "block";
}

// Controllers for background animation.
var animationPause = false;
var fastControlMsg = document.getElementById("fastControlMsg");

function forwardAnimation() {
    if (animationPause) {
        fastControlMsg.style.display = "block";
        setTimeout(function() { fastControlMsg.style.display = "none"; }, 3500);
        return;
    }
    animationPause = true;
    setTimeout(function() { animationPause = false; }, 1000);

    if (animationStop) {
        animationStop = false;
        updateBackgroundImage();
        animationStop = true;
        clearTimeout(animationTimeout);
    }
    else {
        updateBackgroundImage();
    }
}

function backwardAnimation() {
    if (animationPause) {
        fastControlMsg.style.display = "block";
        setTimeout(function() { fastControlMsg.style.display = "none"; }, 3000);
        return;
    }
    animationPause = true;
    setTimeout(function() { animationPause = false; }, 1000);

    reverse = "Reverse";
    imageIteratorNext -= 2;
    if (imageIteratorNext == -1)
        imageIteratorNext = headerImages.length - 1;
    else if (imageIteratorNext == -2)
        imageIteratorNext = headerImages.length - 2;

    if (animationStop) {
        animationStop = false;
        updateBackgroundImage();
        animationStop = true;
        clearTimeout(animationTimeout);
    }

    else
        updateBackgroundImage();
    reverse = "";
}

function toggleAnimation() {
    if (animationPause) {
        fastControlMsg.style.display = "block";
        setTimeout(function() { fastControlMsg.style.display = "none"; }, 3000);
        return;
    }
    animationPause = true;
    setTimeout(function() { animationPause = false; }, 1000);

    var toggleIcon = document.getElementById("toggleIcon");
    if (toggleIcon.classList.contains("fa-pause")) {
        toggleIcon.classList.remove("fa-pause");
        toggleIcon.classList.add("fa-play");
        animationStop = true;
        clearTimeout(animationTimeout);
    }
    else {
        toggleIcon.classList.remove("fa-play");
        toggleIcon.classList.add("fa-pause");
        animationStop = false;
        updateBackgroundImage();
    }
}


// Controller button for downloading background image.
function getFileName(str) {
    return str.substring(str.lastIndexOf('/') + 1)
}

function downloadBackground() {
    var currentImage = headerImages[imagesOrder[imageIterator]];
    var imagePath = currentImage.getAttribute("src");
    var fileName = getFileName(imagePath);
    location.href = "/img/backgrounds/" + fileName;
}


// Controller button for theme toggle.
function toggleTheme() {
    location.href += "?theme=suckless";
}


// Functions for the heartbeat.
var heartAnimation;

function beatHeart() {
    clearTimeout(heartAnimation);
    var heart = document.getElementById("heart");
    heart.style.visibility = "visible";
    heart.style.opacity = 1;
    confetti.start(2000);
}

function hideHeart() {
    var heart = document.getElementById("heart");
    heart.style.opacity = 0;
    heartAnimation = setTimeout(function () {
        heart.style.visibility = "hidden";
    }, 1100);
}


// Add click event to relative links to show bouncing pointing hand to anchor.
// Also add the posibility to go back.
var links = document.getElementsByTagName("a");

for (var i = 0; i < links.length; ++i) {
    if (links[i].hash)
        links[i].addEventListener("click", showBouncingHand);
}

var anchorUndoDistance;

function stopStartedAnchor() {
    if (!anchor)
        return;

    var bouncingHand = anchor.children[0];
    var anchorUndo = anchor.children[1];

    bouncingHand.style.visibility = "hidden";

    var anchorUndoIcon = anchorUndo.children[0];
    anchorUndoIcon.style.opacity = 0;
    anchor.style.visibility = "hidden";
}

function anchorToggle() {
    if (!anchor)
        return;

    var bouncingHand = anchor.children[0];
    var anchorUndo = anchor.children[1];

    bouncingHand.style.opacity = 0;
    anchorUndo.style.visibility = "visible";
    var anchorUndoIcon = anchorUndo.children[0];
    anchorUndoIcon.style.opacity = .75;
}

var anchor;
var anchorToggleTimeout;
var anchorCloseTimeout;

function showBouncingHand(event) {
    anchorToggle();
    clearTimeout(anchorToggleTimeout);
    stopStartedAnchor();
    clearTimeout(anchorCloseTimeout);

    anchorUndoDistance = document.scrollingElement.scrollTop;

    var linkHref;
    if (event.target.classList.contains("linkIcon"))
        linkHref = event.target.offsetParent.hash;
    else
        linkHref = event.target.hash;
    anchor = document.getElementById(linkHref.substring(1, linkHref.length));
    var bouncingHand = anchor.children[0];
    var anchorUndo = anchor.children[1];

    anchor.style.visibility = "visible";
    bouncingHand.style.visibility = "visible";
    bouncingHand.style.opacity = .75;

    anchorToggleTimeout = setTimeout(function () {
        anchorToggle();
        anchorCloseTimeout = setTimeout(stopStartedAnchor, 18000);
    }, 2750);
}

function takeMeBack() {
    window.scroll({top: anchorUndoDistance});
    stopStartedAnchor();
    clearTimeout(anchorCloseTimeout);
}


// For @media print settings.
window.addEventListener("beforeprint", loadingSpinner);
