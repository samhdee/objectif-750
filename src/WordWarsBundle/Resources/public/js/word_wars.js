function count_my_time(date, temps_diff) {
  var diff = {};
  diff.temps = Math.floor(temps_diff/1000);
  diff.secondes = diff.temps % 60;
  diff.minutes = Math.floor(diff.temps/60) % 60;
  diff.heures = Math.floor(diff.temps/3600) % 24;

  return diff;
}

( function($) {
  $(document).ready(function() {
    $('#mywordwar_wrapper textarea#my_wordwar_words').autogrow();

    $('a#focus_me').on('click', function(e) {
      e.preventDefault();
      $('#mywordwar_wrapper').toggleClass('distraction_free');
    });

    $('#mywordwar_wrapper textarea#my_wordwar_words').on('keyup', function(event) {
      count_my_words();
    });

    // Init des variables globales
    var ww_in_progress = false;
    var autosave_interval_id = null;
    var past_content = '';

    // Mais on vérifie quand même
    check_ww_started();

    // =====================
    // Functions
    // =====================
    function check_ww_started() {
      // Initialisation des objets Date now, début et fin
      var now        = new Date();

      start_time = $('#start_time').val();
      var start_date = new Date(start_time);

      end_time   = $('#end_time').val();
      var end_date   = new Date(end_time);

      // On vérifie si la WW a commencé
      if(start_date > now) {
        // Si elle n'a pas commencé, on lance le CD et on désactive le textarea
        $('#mywordwar_wrapper textarea#my_wordwar_words').hide();
        $('#mywordwar_wrapper textarea#my_wordwar_words').prop('disabled', true);

        var temps_diff = new Date(start_date - now);
        countdown = count_my_time(start_date, temps_diff);
        begin_countdown($('#giant_starting_coutdown'), false, countdown);
      }
      else {
        // Si elle a commencé on affiche le textarea et on lance le CD
        $('#giant_starting_coutdown').hide();
        $('#mywordwar_wrapper textarea#my_wordwar_words').show();
        $('#mywordwar_wrapper textarea#my_wordwar_words').prop('disabled', false);

        // On met à jour le flag de WW commencée et on lance le CD et l'autosave
        ww_in_progress = true;

        var temps_diff = new Date(end_date - now);
        countdown = count_my_time(end_date, temps_diff);
        begin_countdown($('p#countdown'), true, countdown);
        auto_save();
      }
    };

    function begin_countdown(which_one, ww_started, countdown) {
      // On décrémente le compteur toutes les secondes
      interval_id = setInterval(function() {
        // On décrémente les secondes
        countdown.secondes -= 1;
        countdown.temps -= 1;

        // Pour refaire partir le nombre de secondes et minutes à 59 une fois à 0
        // Et décrémenter les minutes quand secondes = 0
        format_countdown(countdown);

        // Affichage du résultat formatté de partout
        update_countdown(countdown, $(which_one));

        // Fin du countdown !
        if(countdown.temps == 0) {
          // On signale que la WW commence
          if(!ww_started) {
            ww_started = true;

            // On rappelle la fonction pour lancer l'auto save
            check_ww_started();
          }
          else {
            // Fin de la WW : posez les stylos, on disable le textarea
            $('#mywordwar_wrapper textarea#my_wordwar_words').prop('disabled', true);
            clearInterval(autosave_interval_id);
          }

          // Dans tous les cas on désactive le setInterval
          clearInterval(interval_id);
          interval_id = null;
        }
      }, 1000);
    };

    function update_countdown(countdown, which_one) {
      $(which_one).find('.hours').html(countdown.heures);
      $(which_one).find('.minutes').html(countdown.minutes);
      $(which_one).find('.secondes').html(countdown.secondes);
    };

    function format_countdown(countdown) {
      if(countdown.secondes == -1) {
        countdown.secondes = 59;
        countdown.minutes = countdown.minutes - 1;

        if(countdown.minutes == -1) {
          countdown.minutes = 59;
          countdown.heures = countdown.heures - 1;
        }
      }

      // On force la converstion integer -> string comme des gros sales
      countdown.secondes = '' + countdown.secondes;
      countdown.minutes = '' + countdown.minutes;

      // Ajout des 0 initiaux si besoin
      if(countdown.secondes.length < 2) {
        countdown.secondes = "0" + countdown.secondes;
      }

      if(countdown.minutes.length < 2) {
        countdown.minutes = "0" + countdown.minutes;
      }
    };

    function auto_save() {
      // On sauvegarde automatiquement toutes les 15 secondes
      autosave_interval_id = window.setInterval(function() {
        words = $('#mywordwar_wrapper textarea#my_wordwar_words').val();

        if('' === past_content) {
          past_content = words;
        }
        else if(words === past_content) {
          return false;
        }

        if(ww_in_progress) {
          save_words();
        }
      }
      , 15000);
    };

    function count_my_words() {
      var words = $('#mywordwar_wrapper textarea#my_wordwar_words').val();
      var regexp = / ?[,-?!.\'";:*_/()]+ ?/g;
      var result = regexp[Symbol.replace](words, ' ').trim();
      var tab = result.split(' ');
      tab = tab.filter(function(n) { return n !== ''});
      var word_count = tab.length;

      $('span#word_counter').html(word_count);
      return word_count;
    };

    function save_words() {
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