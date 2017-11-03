var crop_texts = function() {
  $.each($('#text_lists .archive_content'), function() {
    if($(this).height() < 150) {
      $(this).find('.show_more').hide();
    }
    else {
      $(this).animate({'height': 150}, 500);
    }
  });
};

( function($) {
  $(document).one('ready', function() {
    crop_texts();
  });

  $(document).ready(function() {
    $('#archives_filters .btn_filter').on('click', function() {
      var url = Routing.generate('my_stats_filter_archives');

      if(!$(this).hasClass('inactive') && 1 == $('.btn_filter.inactive').length) {
        $('.btn_filter.inactive').removeClass('inactive');
      }

      $(this).toggleClass('inactive');

      var types = [];

      $.each($('.btn_filter.inactive'), function() {
        types.push($(this).attr('data-filter'));
      });

      var data_filtres = { types: types };

      if('undefined' !== typeof types) {
        $.post(
          url,
          data_filtres,
          function(data) {
            $('#text_lists').fadeOut('200', function() {
              $(this).html(data);
              $(this).fadeIn('200', crop_texts);
            });
          }
        );
      }
    });

    $('#text_lists').on('click', '.show_more', function() {
      var auto_height = $(this).parents('.archive_content').css('height', 'auto').height();

      $(this).parents('.archive_content').height(150).animate({'height': (auto_height + 60)}, 500);
      $(this).html('moins...');
      $(this).addClass('show_less');
      $(this).removeClass('show_more');
    });

    $('#text_lists').on('click', '.show_less', function() {
      $(this).parents('.archive_content').animate({'height': 150}, 500);
      $(this).html('plus...');
      $(this).removeClass('show_less');
      $(this).addClass('show_more');
    });
  });
} )( jQuery );