// Configuration variables.
var scrollStep = 100;
var maxCommandCounter = 10;
var showInformationVar = true;

// Counters to identify visitor.
var emacsCommandCounter = 0;
var vimCommandCounter = 0;
var noobCommandCounter = 0;


function showInformation() {
    if (showInformationVar === false)
        return;

    if (emacsCommandCounter === maxCommandCounter) {
        document.getElementById("blurBackground").style.display = "block";
        document.getElementById("welcomeEmacsUser").classList.add("bounceInFromRight");
        document.getElementById("welcomeEmacsUser").style.display = "block";
        showInformationVar = false;
        return;
    }
    if (vimCommandCounter === maxCommandCounter) {
        document.getElementById("blurBackground").style.display = "block";
        document.getElementById("welcomeVimUser").classList.add("bounceInFromRight");
        document.getElementById("welcomeVimUser").style.display = "block";
        showInformationVar = false;
        return;
    }
    if (noobCommandCounter === maxCommandCounter) {
        document.getElementById("blurBackground").style.display = "block";
        document.getElementById("welcomeNoobUser").classList.add("bounceInFromRight");
        document.getElementById("welcomeNoobUser").style.display = "block";
        showInformationVar = false;
    }
}


function closeInformation() {
    document.getElementById("blurBackground").style.display = "none";
    navBarExpanded.style.display = "none";
    navBar.style.display = "block";
    mainDiv.classList.remove("mainShrunken");

    var infoDivs = document.getElementsByClassName("info");
    for (var i = 0; i < infoDivs.length; ++i)
        infoDivs[i].style.display = "none";
}


// Scroll to next o previous section depending on sign parameter.
function scrollToSection(sign) {
    var sections = document.getElementsByTagName("section");
    var navBar = document.getElementsByTagName("nav")[0];
    var navBarLinks = navBar.getElementsByTagName("a");
    var windowHeight = window.innerHeight;

    for (var i = 0; i < sections.length; ++i) {
        var positionFromBottom = sections[i].getBoundingClientRect().bottom;

        if (sign < 0)
            windowHeight /= 2; // Hacky trick for small height sections.

        if (positionFromBottom > windowHeight / 2) {
            navBarLinks[i + sign].click();
            break;
        }
    }
}


// Add keyboard shortcuts.
function keyboardShortcuts(event) {
    if (event.target.nodeName === "INPUT" || event.target.nodeName === "TEXTAREA")
        return;

    switch (event.key) {
        // Emacs-like commands.
    case "n":
        window.scrollBy(0, scrollStep);
        ++emacsCommandCounter;
        break;
    case "p":
        window.scrollBy(0, -scrollStep);
        ++emacsCommandCounter;
        break;
    case "f":
        scrollToSection(1);
        ++emacsCommandCounter;
        break;
    case "b":
        scrollToSection(-1);
        ++emacsCommandCounter;
        break;
    case ">":
        window.scrollBy(0, document.documentElement.offsetHeight);
        ++emacsCommandCounter;
        break;
    case "<":
        window.scrollTo(0, 0);
        ++emacsCommandCounter;
        break;

        // Vim-like commands.
    case "j":
        window.scrollBy(0, scrollStep);
        ++vimCommandCounter;
        break;
    case "k":
        window.scrollBy(0, -scrollStep);
        ++vimCommandCounter;
        break;
    case "l":
        scrollToSection(+1);
        ++vimCommandCounter;
        break;
    case "h":
        scrollToSection(-1);
        ++vimCommandCounter;
        break;
    case "g":
        window.scrollTo(0, 0);
        ++vimCommandCounter;
        break;
    case "G":
        window.scrollBy(0, document.documentElement.offsetHeight);
        ++vimCommandCounter;
        break;
    case "q":
        closeInformation();
        ++vimCommandCounter;
        break;
    case "d":
        window.scrollBy(0, 3 * scrollStep);
        ++vimCommandCounter;
        break;
    case "u":
        window.scrollBy(0, -3 * scrollStep);
        ++vimCommandCounter;
        break;

        // Count noob commands.
    case "ArrowDown":
        ++noobCommandCounter;
        break;
    case "ArrowUp":
        ++noobCommandCounter;
        break;

        // Other commands.
    case "m":
        toggleNavBar();
        break;
    case "Escape":
        closeInformation();
        break;
    case "?":
        document.getElementById("blurBackground").style.display = "block";
        document.getElementById("welcomeNoobUser").classList.add("bounceInFromRight");
        document.getElementById("welcomeNoobUser").style.display = "block";
        break;
    case "H":
        document.getElementById("blurBackground").style.display = "block";
        document.getElementById("welcomeNoobUser").classList.add("bounceInFromRight");
        document.getElementById("welcomeNoobUser").style.display = "block";
        break;
    case " ":
        toggleAnimation();
        break;
    case "ArrowLeft":
        forwardAnimation();
        break;
    case "ArrowRight":
        backwardAnimation();
        break;
    }

    showInformation();
}

window.addEventListener("keydown", keyboardShortcuts);
