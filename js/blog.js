/**
 *
 * @source: https://blog.alvarezrosa.com/js/home.js
 *
 * @licstart  The following is the entire license notice for the
 *  JavaScript code in this page.
 *
 * Copyright (C) 2019-2020  David \'Alvarez Rosa
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

function closeZoomImg() {
    zoomImgDiv.style.display = "none";
}

function closeZoomImgAux(event) {
    if (zoomImg.contains(event.target) || zoomImgCaption.contains(event.target))
        return;
    zoomImgDiv.style.display = "none";
}

zoomImgDiv.addEventListener("click", closeZoomImgAux);


// Copy text to clipboard. Used for copying source code.
function copyToClipboard(textId) {
    var copyText = document.getElementById(textId);
    var textParent = copyText.parentElement.parentElement;
    var text = copyText.textContent;
    var copyButton = textParent.getElementsByClassName("copy")[0];

    var id = "mycustom-clipboard-textarea-hidden-id";
    var existsTextarea = document.getElementById(id);

    if (!existsTextarea){
        var textarea = document.createElement("textarea");
        textarea.id = id;
        textarea.style.position = "fixed";
        textarea.style.top = 0;
        textarea.style.left = 0;
        textarea.style.width = "1px";
        textarea.style.height = "1px";
        textarea.style.padding = 0;
        textarea.style.border = "none";
        textarea.style.outline = "none";
        textarea.style.boxShadow = "none";
        textarea.style.background = "transparent";
        document.querySelector("body").appendChild(textarea);
        existsTextarea = document.getElementById(id);
    }

    existsTextarea.value = text;
    existsTextarea.select();

    try {
        var status = document.execCommand("copy");
        if(!status) {
            copyButton.style.display = "none";
            var copyError = textParent.getElementsByClassName("copyError")[0];
            copyError.style.visibility = "visible";
            copyError.style.opacity = 1;
            setTimeout(function () {
                copyError.style.visibility = "hidden";
                copyError.style.opacity = 0;
                copyButton.style.display = "block";
            }, 2500);
        }
        else {
            copyButton.style.display = "none";
            var copyOkey = textParent.getElementsByClassName("copyOkey")[0];
            copyOkey.style.visibility = "visible";
            copyOkey.style.opacity = 1;
            setTimeout(function () {
                copyOkey.style.visibility = "hidden";
                copyOkey.style.opacity = 0;
                copyButton.style.display = "block";
            }, 2500);
        }
    }
    catch (err) {
        copyButton.style.display = "none";
        var copyError = textParent.getElementsByClassName("copyError")[0];
        copyError.style.visibility = "visible";
        copyError.style.opacity = 1;
        setTimeout(function () {
            copyError.style.visibility = "hidden";
            copyError.style.opacity = 0;
            copyButton.style.display = "block";
        }, 2500);
    }
}


// Enlarge call out.
var enlargedCallOutDiv = document.getElementById("enlargedCallOutDiv");
var enlargedCallOutInfo = document.getElementById("enlargedCallOutInfo");

function enlargeCallOut(callOutId) {
    enlargedCallOutDiv.style.display = "block";
    var callOut = document.getElementById(callOutId);
    var callOutSpan = callOut.lastElementChild;
    enlargedCallOutInfo.innerHTML = callOutSpan.innerHTML;
}

function closeEnlargedCallOut() {
    enlargedCallOutDiv.style.display = "none";
}

function closeEnlargedCallOutAux(event) {
    if (enlargedCallOutInfo.contains(event.target))
        return;
    enlargedCallOutDiv.style.display = "none";
}

enlargedCallOutDiv.addEventListener("click", closeEnlargedCallOutAux);
