// Add keyboard shortcuts.

var scrollStep = 100;
var emacsCommandCounter = 0;

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
