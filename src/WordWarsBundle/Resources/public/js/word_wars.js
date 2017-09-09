function count_my_time() {
  var end = $('#end_time').val();

  if('undefined' !== typeof end) {
    var end_date = new Date(end);
    var now = new Date();

    var diff = {};
    var temps_diff = new Date(end_date - now);
    var temps = Math.floor(temps_diff/1000);
    diff.secondes = temps % 60;
    diff.minutes = Math.floor(temps/60) % 60;
    diff.heures = Math.floor(temps/3600) % 24;

    return diff;
  }

  return false;
}

( function($) {
  $(document).ready(function() {
    $('#my_wordwar_words').autogrow();

    $('a#clickme').on('click', function(e) {
      e.preventDefault();
      $('#mywordwar_wrapper').toggleClass('distraction_free');
    });

    var past_content = '';

    // window.setInterval(function() {
    //   var words = $('#mywordwar_wrapper textarea#my_wordwar_words').val();

    //   if('' === past_content) {
    //     past_content = words;
    //   }
    //   else if(words === past_content) {
    //     return false;
    //   }

    //   save_words();
    // }
    // , 15000);

    var countdown = count_my_time();
    update_countdown;

    window.setInterval(function() {
      countdown.secondes = countdown.secondes - 1;

      if(countdown.secondes == -1) {
        countdown.secondes = 59;
        countdown.minutes = countdown.minutes - 1;

        if(countdown.minutes == -1) {
          countdown.minutes = 59;
          countdown.heures = countdown.heures - 1;
        }
      }

      update_countdown();
    }, 1000);

    $(window).keypress('s', function(e) {
      if(e.ctrlKey) {
        e.preventDefault();
        var words = $('#mywordwar_wrapper textarea#my_wordwar_words').val();

        if('' === past_content) {
          past_content = words;
        }
        else if(words === past_content) {
          return false;
        }

        save_words();
      }
    });

    $('#mywordwar_wrapper textarea#my_wordwar_words').on('keyup', function(event) {
      count_my_words();
    });

    // $('#test_button').on('click', function (e) {
    //   e.preventDefault();
    //   // count_my_time();
    // });

    var update_countdown = function() {
      $('p#countdown #hours').html(countdown.heures);
      $('p#countdown #minutes').html(countdown.minutes);
      $('p#countdown #secondes').html(countdown.secondes);
    }

    var count_my_words = function() {
      var words = $('#mywordwar_wrapper textarea#my_wordwar_words').val();
      var regexp = / ?[,-?!.\'";:*_/()]+ ?/g;
      var result = regexp[Symbol.replace](words, ' ').trim();
      var tab = result.split(' ');
      tab = tab.filter(function(n) { return n !== ''});
      var word_count = tab.length;

      $('span#word_counter').html(word_count);
      return word_count;
    }

    var save_words = function() {
      var url = Routing.generate('save_my_ww_words');
      var content = $('#mywordwar_wrapper textarea#my_wordwar_words').val();
      var word_count = count_my_words();
      var ww_id = $('#ww_id').val();

      $('#progression_status').html();

      if(content) {
        $.post(
          url,
          {
            'ww_id': ww_id,
            'content': content,
            'word_count': word_count
          },
          function(data) {
            if(data.status == 'ok') {
              $('#progression_status')
                .html(data.message)
                .addClass('ok')
                .fadeIn()
                .delay(2000)
                .fadeOut('slow');
            }
          });
      }
    };
  })
} )( jQuery );