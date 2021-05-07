let displayTricks = document.getElementById('display-more-tricks');

let moreTricks = document.getElementsByClassName('above-max');

let allTricks = document.getElementsByClassName('single-trick-preview');

checkDisplayBlock(allTricks);

displayTricks.onclick = function displayComments() {
    for (var i = 0, len = moreTricks.length; i < len; i++) {
        moreTricks[i].style.display = "block";
    }
    this.style.display = "none";

    checkDisplayBlock(allTricks);
}

function checkDisplayBlock(array) {

    let blockResult = 0;

    for (var i = 0, len = array.length; i < len; i++) {
        result = array[i].style.display;

        if (result === "block") {
            blockResult++;
        }
    }

    if (blockResult > 15) {
        document.getElementById('to-top').style.display = "block";
    }

}