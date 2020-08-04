export default {
  init() {
    // JavaScript to be fired on the home page
    var vals = [$('.student-info tr'), $('.student-info tr.good-to-go'), $('.student-info tr.stay-home'), $('.student-info tr.not-checked-in')];
    $('.information__select').change(function(){
        $('.student-info tr:nth-child(n + 2)').remove();
        $('.student-info').append(vals[$(this).val()]);
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
