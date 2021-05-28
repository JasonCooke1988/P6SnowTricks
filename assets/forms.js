//Insert file name into file upload input field
$(document).on('input', function (e) {
    //$('.custom-file-label').html(fileName);
    if (e.target.files !== undefined && e.target.files !== null) {
        var fileName = e.target.files[0].name;
        $(e.target).siblings('.custom-file-label').html(fileName);
    }
});