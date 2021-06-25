$('.modify-image, .modify-video').on('click', function (e) {
   e.preventDefault();
   if ($(this).hasClass('modify-image')) {
      $(this).closest('.image-wrapper').find('input').removeClass('hide-input');
      console.log($(this).closest('.image-wrapper').find('input'))
   } else if ($(this).hasClass('modify-video')) {
      $(this).closest('.video-wrapper').find('textarea').removeClass('hide-input');
      console.log($(this).closest('.video-wrapper').find('input'))
   }
})