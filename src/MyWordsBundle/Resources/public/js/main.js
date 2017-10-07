( function($) {
  $(document).ready(function() {
    $('#my_daily_words').autogrow();

    $('button#clickme').on('click', function(e) {
      e.preventDefault();
      $('#daily_words_wrapper').toggleClass('distraction_free');
    });

    var past_content = '';

    window.setInterval(function() {
      var words = $('#daily_words_wrapper textarea#my_daily_words').val();

      if('' === past_content) {
        past_content = words;
      }
      else if(words === past_content) {
        return false;
      }

      save_words();
      // count_my_words();
    }
    , 15000);

    $('button#save_me').on('click', function(e) {
      e.preventDefault();
      var words = $('#daily_words_wrapper textarea#my_daily_words').val();

      if('' === past_content) {
        past_content = words;
      }
      else if(words === past_content) {
        return false;
      }

      save_words();
    });

    $('#daily_words_wrapper textarea#my_daily_words').on('keyup', function(event) {
      var word_count = count_my_words();
      $('span#word_counter').html(word_count);

      var todays_goal = $('input#todays_goal').val();

      if(word_count == todays_goal) {
        $('#word_counter:not(.quota_reached)').addClass('quota_reached');
        $('#word_counter.quota_reached').prop({
          title: 'Félicitations ! Vous avez atteint votre quota journalier. Mais vous pouvez continuer sur votre lancée si vous voulez.'
        });
      }
      else if(word_count < todays_goal) {
        $('#word_counter.quota_reached').removeAttr('title');
        $('#word_counter.quota_reached').removeClass('quota_reached');
      }
    });

    var save_words = function() {
      var url = Routing.generate('save_my_words');
      var content = $('#daily_words_wrapper textarea#my_daily_words').val();
      $('#progression_status').html();
      var word_count = count_my_words();

      if(content) {
        $.post(
          url,
          {'content': content,
           'word_count': word_count},
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

    var count_my_words = function() {
      var words = $('#daily_words_wrapper textarea#my_daily_words').val();
      var regexp = / ?[,-?!.\'";:*_/()]+ ?/g;
      var result = regexp[Symbol.replace](words, ' ').trim();
      var tab = result.split(' ');
      tab = tab.filter(function(n) { return n !== ''});
      var word_count = tab.length;

      return word_count;
    };
  })
} )( jQuery );