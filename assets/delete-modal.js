// Insert href of appropriate action to delete modal
$("#delete-modal").on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let trick = button.is('#delete-trick');
    let image = button.is('.delete-image');
    let video = button.is('.delete-video');
    let mainImage = button.is('.delete-main-image');

    if (trick) {

        $("#delete-modal .modal-body p").text('Confirmer la suppresion de la figure ?');
        let href = button.data('href')
        $('#delete-modal #delete-submit').attr('href', href);


    } else if (image) {

        $("#delete-modal .modal-body p").text('Confirmer la suppression de l\'image ? (N’oubliez pas de sauvegarder vos changements)');
        $("#delete-submit").text('Confirmer');

        $('#delete-submit').on('click', function (e) {
            e.preventDefault()
            button.closest('.image-wrapper').remove();
            $('#delete-modal').modal('hide');
        });

    } else if (video) {

        $("#delete-modal .modal-body p").text('Confirmer la suppression de la vidéo ? (N’oubliez pas de sauvegarder vos changements)');
        $("#delete-submit").text('Confirmer');

        $('#delete-submit').on('click', function (e) {
            e.preventDefault()
            button.closest('.video-wrapper').remove();
            $('#delete-modal').modal('hide');
        });

    } else if (mainImage) {
        $("#delete-modal .modal-body p").text('Confirmer la suppression de l\'image principale ?');
        let href = button.data('href')
        $('#delete-modal #delete-submit').attr('href', href);

    }

});