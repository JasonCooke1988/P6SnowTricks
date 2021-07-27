let displayComments = document.getElementById('display-more-comments');

let displayMedias = document.getElementById('display-medias');

let mediaRow = document.getElementById('media-row');

displayComments.onclick = function () {

    let moreComments = document.getElementsByClassName('above-max');
    let length = moreComments.length;

    while (moreComments.length > length - 10 && moreComments.length > 0) {
        moreComments[0].classList.remove('above-max');
    }

    if (moreComments.length === 0) {
        displayComments.style.display = "none";
    }
}

displayMedias.onclick = function () {
    mediaRow.style.display = "flex"
    this.style.display = "none";
}