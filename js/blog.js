window.addEventListener("load", function() {
    setTimeout(loadMathJax, 3000);
})

function loadMathJax() {
    var mathJaxScript = document.getElementById("MathJax-script");
    mathJaxScript.src = mathJaxScript.dataset.src;
}
