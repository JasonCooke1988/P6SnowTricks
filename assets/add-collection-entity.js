// add-collection-widget.js
$(document).ready(function () {
    if ($('main').hasClass('modify-trick')) {
        var imageCounter = $('.image-wrapper').length;
        var videoCounter = $('.video-wrapper').length;
    } else {
        var imageCounter = 0;
        var videoCounter = 0;
    }
    $('#add-another-image, #add-another-video').click(function (e) {
        e.preventDefault()
        var listImage = $('.image-list');
        var listVideo = $('.video-list');
        if (e.target.id === 'add-another-image') {
            var newWidget = listImage.attr('data-prototype');
            newWidget = newWidget.replace(/__name__/g, imageCounter);
            imageCounter++;
            listImage.data('widget-counter', imageCounter);
            let newElem = $(listImage.attr('data-widget-tags')).html(newWidget);
            newElem.appendTo(listImage);
        } else {
            var newWidget = listVideo.attr('data-prototype');
            newWidget = newWidget.replace(/__name__/g, videoCounter);
            videoCounter++;
            listVideo.data('widget-counter', videoCounter);
            let newElem = $(listVideo.attr('data-widget-tags')).html(newWidget);
            newElem.appendTo(listVideo);
        }
    });
});