let displayComments = document.getElementById('display-more-comments');

let displayMedias = document.getElementById('display-medias');

let mediaRow = document.getElementById('media-row');

let moreComments = document.getElementsByClassName('above-max');

commentsMore();

displayComments.onclick = function () {

    let length = moreComments.length;

    while (moreComments.length > length - 10 && moreComments.length > 0) {
        moreComments[0].classList.remove('above-max');
    }

    commentsMore();
}

displayMedias.onclick = function () {
    mediaRow.style.display = "flex"
    this.style.display = "none";
}

function commentsMore() {
    if (moreComments.length === 0) {
        displayComments.style.display = "none";
    }
}