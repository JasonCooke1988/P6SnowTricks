// Insert href of appropriate action to delete modal
$("#delete-modal").on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let trick = button.is('#delete-trick-submit');
    if (trick) {
        $("#delete-modal .modal-body p").text('Confirmer la suppresion de la figure ?');
    }
    let href = button.data('href')
    $('#delete-modal #delete-submit').attr('href', href);
});