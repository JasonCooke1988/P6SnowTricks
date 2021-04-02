let displayComments = document.getElementById('display-more-comments');

let moreComments = document.getElementsByClassName('above-max');

let allComments = document.getElementsByClassName('single-comment');

let displayMedias = document.getElementById('display-medias');

let mediaRow = document.getElementById('media-row');

if (allComments.length < 5) {
    displayComments.style.display = "none";
}

displayComments.onclick = function () {
    for (var i = 0, len = moreComments.length; i < len; i++) {
        moreComments[i].style.display = "flex";
    }
    this.style.display = "none";
}

displayMedias.onclick = function() {
    mediaRow.style.display = "flex"
    this.style.display = "none";
}

console.log('coucou');