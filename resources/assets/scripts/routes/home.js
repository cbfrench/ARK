import Tesseract from 'tesseract.js';

export default {
  init() {
    // JavaScript to be fired on the home page
    $('.number-of-students__button').on('input', function(){
      if($(this).val() === '' || $(this).val() < 1) return;
      var number_of_students = $(this).val();
      $('.student').each(function(ind){
        if(ind < number_of_students) $(this).slideDown(500);
        else $(this).slideUp(500);
      });
    });
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
    $('body').click(function(){
      $('.student-error').fadeOut(500);
    });
    setTimeout(function(){
      $('.student-error').fadeOut(500);
    }, 5000);

    Tesseract.recognize('/wp-content/uploads/2020/07/test3.jpg', 'eng', {
      tessedit_char_whitelist: '0123456789',
    }).then(({data: {text}}) => {
      console.log(text);
    });

  },
};
