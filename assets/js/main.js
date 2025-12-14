document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('img').forEach(function (img) {
        if (img.closest('a')) return;

        const link = document.createElement('a');
        link.href = img.src;
        img.parentNode.insertBefore(link, img);
        link.appendChild(img);
    });
});

document.querySelectorAll("p").forEach(p => {
    p.innerHTML = p.innerHTML.replace(/^(?:&nbsp;|\s)+/, '');
});
