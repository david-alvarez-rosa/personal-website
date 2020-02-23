window.addEventListener("load", function() {
    setTimeout(loadMathJax, 3000);
})

function loadMathJax() {
    MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        },
        svg: {
            fontCache: 'global'
        }
    };

    var mathJaxScript = document.getElementById("MathJax-script");
    mathJaxScript.src = mathJaxScript.dataset.src;
}
