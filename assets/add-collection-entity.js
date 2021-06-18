// add-collection-widget.js
$(document).ready(function () {
    if ($('main').hasClass('modify-trick')) {
        var counter = $('.image-wrapper').length;
        console.log('yo')
    } else {
        var counter = 0;
    }
    $('#add-another-image, #add-another-video').click(function (e) {
        if (e.target.id === 'add-another-image') {
            var list = $('.image-list');
        } else {
            var list = $('.video-list');
        }
        // grab the prototype template
        let newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);
        // create a new list element and add it to the list
        //let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});