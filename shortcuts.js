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
    }
}

window.addEventListener("keydown", keyboardShortcuts);
