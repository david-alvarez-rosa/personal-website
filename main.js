// Add loading spinner animation while page is loading.
window.addEventListener("load", loadingSpinner);
function loadingSpinner() {
    document.getElementById("loadingSpinner").classList.add("hideLoadingSpinner");
    document.getElementsByTagName("body")[0].classList.remove("preload");
    setTimeout(setNavBarSection, 1000); // Ensure navBar secion updates.

    // Warn that this website is under construction.
    setTimeout( function() { showInfo("welcomeUser"); }, 3500);
}


// Automatically update the background image.
var imageIterator = 0;
var imageIteratorNext = 1;
var images = document.getElementsByClassName("backgroundImage");
var colors = ["#D45B12", "#006579", "#C98E03", "#3A6F1F", "#DF7E3A", "#56737B", "#405172"];

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

var imagesOrder = [0, 1, 2, 3, 4, 5, 6];
imagesOrder = randomShuffle(imagesOrder);
var header = document.getElementsByTagName("header")[0];
var animationDuration = 15000; // In miliseconds.
var reverse = "";
var animationStop = false;
var animationTimeout, animationTimeoutAux;


function updateBackgroundImage() {
    clearTimeout(animationTimeoutAux);

    if (animationStop)
        return;

    var currentImage = images[imagesOrder[imageIterator]];
    currentImage.classList.remove("imageIn", "imageInReverse");
    currentImage.classList.add("imageOut" + reverse);

    var nextImage = images[imagesOrder[imageIteratorNext]];
    nextImage.classList.add("imageIn" + reverse);
    nextImage.classList.remove("imageOut", "imageOutReverse");

    animationTimeout = setTimeout(function() {
        nextImage.classList.remove("imageIn", "imageInReverse");
    }, animationDuration);

    setTimeout(function() {
        document.documentElement.style.setProperty("--main-color",
                                                   colors[imagesOrder[imageIteratorNext]]);
    }, 500);
    header.style.backgroundColor = colors[imagesOrder[imageIteratorNext]]

    setTimeout(function() {
        imageIterator = imageIteratorNext;
        if (imageIteratorNext == images.length - 1)
            imageIteratorNext = 0
        else
            ++imageIteratorNext;
    }, 750);

    animationTimeoutAux = setTimeout( function() {
        updateBackgroundImage();
    }, animationDuration);
}

var firstImage = images[imagesOrder[imageIterator]];
firstImage.src = "images/backgrounds/" + firstImage.dataset.src + "_low.jpg";
firstImage.classList.add("backgroundImageFirst");
document.documentElement.style.setProperty("--main-color",
                                           colors[imagesOrder[imageIterator]]);
setTimeout(function() {
    firstImage.classList.remove("backgroundImageFirst");
    updateBackgroundImage();
}, animationDuration - 5000);


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
    for (var i = 0; i < images.length; ++i) {
        var image = images[imagesOrder[i]];
        image.src = "images/backgrounds/" + image.dataset.src + ".jpg";
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

function toggleNavBar() {
    if (navBarExpanded.style.display === "none") {
        navBarExpanded.style.display = "block";
        navBar.style.display = "none";
        mainDiv.classList.add("mainShrunken");
    }
    else {
        navBarExpanded.style.display = "none";
        navBar.style.display = "block";
        mainDiv.classList.remove("mainShrunken");
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


// Set languages section animation.
var languagesLevels = document.querySelectorAll(".languageLevel");

function languagesLevelsAnimation() {
    var languageSection = languagesLevels[0];
    var positionFromTop = languageSection.getBoundingClientRect().top;

    if (positionFromTop - windowHeight <= -100)
        for (var i = 0; i < languagesLevels.length; ++i) {
            languagesLevels[i].classList.remove("languageLevelQuick");
            languagesLevels[i].classList.add("languageLevelAnimate" + i);
        }

    if (positionFromTop < -500 || positionFromTop > windowHeight + 250)
        for (var i = 0; i < languagesLevels.length; ++i) {
            languagesLevels[i].classList.add("languageLevelQuick");
            languagesLevels[i].classList.remove("languageLevelAnimate" + i);
        }
}

languagesLevelsAnimation();

window.addEventListener("scroll", languagesLevelsAnimation);
window.addEventListener("resize", languagesLevelsAnimation);


// Get nearer section to user position (so navBar can be updated).
var elementCurrent = document.getElementsByClassName("current")[0];
var elementCurrentExpanded = document.getElementsByClassName("current")[1];
var sections = document.getElementsByTagName("section");
var navBar = document.getElementById("navBar");
var navBarDiv = document.getElementById("navBarDiv");
var navBarLinks = navBar.getElementsByTagName("a");
var navBarExpanded = document.getElementById("navBarExpanded");
var navBarExpandedLinks = navBarExpanded.getElementsByTagName("a");

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
    var positionFromRight = elementCurrent.getBoundingClientRect().right;
    if (positionFromRight > windowWidth - 100)
        navBarDiv.scrollLeft += scrollStep;

    // Scroll expanded navigation bar to show current section.
    var positionFromBottom = elementCurrentExpanded.getBoundingClientRect().bottom;
    if (positionFromBottom > windowHeight - 15)
        navBarExpanded.scrollTop += windowHeight / 6;
    var positionFromTop = elementCurrentExpanded.getBoundingClientRect().top;
    if (positionFromTop < 15)
        navBarExpanded.scrollTop -= windowHeight / 6;
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

            scrollNavBarToView();
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
    if (document.documentElement.scrollTop > 100 || window.innerWidth <= 980) {
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
    if (document.getElementById("blurBackground").contains(event.target) ||
        document.getElementsByTagName("main")[0].contains(event.target) ||
        navBarExpanded.contains(event.target))
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


// Pie interestsChart interests section.
window.chartColors = {
    red: "rgb(255, 99, 132)",
    orange: "rgb(255, 159, 64)",
    yellow: "rgb(255, 205, 86)",
    green: "rgb(75, 192, 192)",
    blue: "rgb(54, 162, 235)",
    purple: "rgb(153, 102, 255)",
    grey: "rgb(201, 203, 207)"
};
var config = {
    type: "pie",
    data: {
        datasets: [{
            data: [100, 0, 0, 0, 0],
            backgroundColor: [
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.yellow,
                window.chartColors.green,
                window.chartColors.blue,
            ]
        }],
        labels: [
            "Automation",
            "Robotics",
            "Sport",
            "Data analysis",
            "Family and friends"
        ]
    },
    options: {
        aspectRatio: 1,
        animation: {
            duration: 2500,
        }
    }
};

var interestsChart = document.getElementById("interestsChart");
var context = document.getElementById("interestsChartArea").getContext("2d");
var chartAnimationFinished = true;

function chartAnimation() {
    var positionFromTop = interestsChart.getBoundingClientRect().top;
    if (positionFromTop <= -350 || positionFromTop >= windowHeight + 100)
        chartAnimationFinished = true;

    if (!chartAnimationFinished)
        return;

    if (positionFromTop - windowHeight <= -250 && positionFromTop >= -100) {
        chartAnimationFinished = false;
        interestsChartPie = new Chart(context, config);
    }
}

chartAnimation();
window.addEventListener("scroll", chartAnimation);
window.addEventListener("resize", chartAnimation);


// Controllers for background animation.
function forwardAnimation() {
    updateBackgroundImage();
}

function backwardAnimation() {
    reverse = "Reverse";
    imageIteratorNext -= 2;
    if (imageIteratorNext == -1)
        imageIteratorNext = images.length - 1;
    else if (imageIteratorNext == -2)
        imageIteratorNext = images.length - 2;
    updateBackgroundImage();
    reverse = "";
}

function toggleAnimation() {
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
