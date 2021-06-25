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
        if (e.target.id === 'add-another-image') {
            var list = $('.image-list');
        } else {
            var list = $('.video-list');
        }
        // grab the prototype template
        let newWidget = list.attr('data-prototype');

        if (list.hasClass('.image-list')) {
            newWidget = newWidget.replace(/__name__/g, imageCounter);
            imageCounter++;
            list.data('widget-counter', imageCounter);
        } else {
            newWidget = newWidget.replace(/__name__/g, videoCounter);
            videoCounter++;
            list.data('widget-counter', videoCounter);
        }

        let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});