//Insert file name into file upload input field
$('input[type="file"]').change(function (e) {
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
});

// Insert image id into modal
$("#single-image-modal").on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id = button.data('imageid')
    $(this).attr('data-imageid', id);
});

// Insert video id into modal
$("#video-modal").on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id = button.data('videoid')
    $('#video-modal').attr('data-videoid', id);
});

//Ajax to pass video data to controller with video id
$('#video-form-submit').on('click',function (e) {
    let form = $('#video-form');
    let videoid =  $('#video-modal').attr('data-videoid');
    $('#trick_form_video_id').val(videoid);
});

//Ajax to pass image data to controller with image id
$('#single-image-form-submit').on('click',function (e) {
    let form = $('#single-image-form');
    let imageid =  $('#single-image-modal').attr('data-imageid');
    $('#trick_form_single_image_id').val(imageid);
});