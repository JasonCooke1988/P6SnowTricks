//let url = window.location.origin;
//let imageInputList = $('.image-input-list div[id^="trick_form_trickImages_"]');
//
//let imageInputIdArray = [];
//
//imageInputList.each(function (index) {
//    imageInputIdArray[index] = $(this).children('input[type="hidden"]').attr('value');
//});
//
//console.log(imageInputIdArray);
//
//$.ajax({
//    type: 'POST',
//    url: url+'/get-image-data',
//    data:{array: imageInputIdArray},
//    dataType:'json',
//    success: function (response, status, xhr) {
//       console.log(response)
//    }
//});