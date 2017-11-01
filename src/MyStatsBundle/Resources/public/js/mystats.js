( function($) {
  $(document).ready(function() {
    $('#archives_filters .btn_filter').on('click', function() {
      var url = Routing.generate('my_stats_filter_archives');
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
              $(this).fadeIn(200);
            });
          }
        );
      }
    });
  });
} )( jQuery );