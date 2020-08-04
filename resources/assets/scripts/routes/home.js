import Tesseract from 'tesseract.js';

export default {
  init() {
    // JavaScript to be fired on the home page
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
