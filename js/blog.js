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


// Add ability to show zoomed images on click.
var main = document.getElementsByTagName("main")[0];
var figures = main.getElementsByTagName("figure");
var zoomImgDiv = document.getElementById("zoomImgDiv");
var zoomImg = document.getElementById("zoomImg");
var zoomImgCaption = document.getElementById("zoomImgCaption");

for (var i = 0; i < figures.length; ++i)
    figures[i].addEventListener("click", openZoomImg);

function openZoomImg() {
    zoomImgDiv.style.display = "block";
    zoomImg.src = this.firstElementChild.src;
    zoomImgCaption.innerHTML = this.lastElementChild.innerHTML
}

zoomImg.src = figures[0].firstElementChild.src;
zoomImgCaption.innerHTML = figures[0].lastElementChild.innerHTML

function closeZoomImg() {
    zoomImgDiv.style.display = "none";
}

function closeZoomImgAux(event) {
    if (zoomImg.contains(event.target) || zoomImgCaption.contains(event.target))
        return;
    zoomImgDiv.style.display = "none";
}

zoomImgDiv.addEventListener("click", closeZoomImgAux);
