// Add keyboard shortcuts.

var scrollStep = 100;
var emacsCommandCounter = 0;
var vimCommandCounter = 0;
var noobCommandCounter = 0;


function showInformation() {
    if (emacsCommandCounter === 10) {
        emacsCommandCounter = vimCommandCounter = noobCommandCounter = 11;
        document.getElementById("welcomeEmacsUser").style.display = "block";
        return;
    }
    if (vimCommandCounter === 10) {
        emacsCommandCounter = vimCommandCounter = noobCommandCounter = 11;
        document.getElementById("welcomeVimUser").style.display = "block";
        return;
    }
    if (noobCommandCounter === 10) {
        emacsCommandCounter = vimCommandCounter = noobCommandCounter = 11;
        document.getElementById("welcomeNoobUser").style.display = "block";
    }
}


function closeAll() {
    document.getElementsByTagName("nav")[0].classList.remove("responsive");
    document.getElementById("welcomeEmacsUser").style.display = "none";
    document.getElementById("welcomeVimUser").style.display = "none";
    document.getElementById("welcomeNoobUser").style.display = "none";
}


function keyboardShortcuts(event) {
    console.log(event.key);

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
    case "g":
        window.scrollTo(0, 0);
        ++vimCommandCounter;
        break;
    case "G":
        window.scrollBy(0, document.documentElement.offsetHeight);
        ++vimCommandCounter;
        break;
    case "q":
        closeAll();
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
    case "ArrowLeft":
        ++noobCommandCounter;
        break;
    case "ArrowRight":
        ++noobCommandCounter;
        break;

        // Other commands.
    case "m":
        toggleNavBar();
        break;
    case "Escape":
        closeAll();
        break;
    }

    showInformation();
}

window.addEventListener("keydown", keyboardShortcuts);


// Hide information divs when click outside.
function navBarHideClick(event) {
    if (event.target.className !== "info" && event.target.id !== "navIcon")
        closeAll();
}

window.addEventListener("click", navBarHideClick);
