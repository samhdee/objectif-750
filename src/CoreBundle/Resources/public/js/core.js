( function($) {
  $(document).ready(function() {
    $('#switch_languages a#current').on('click', function() {
      $('#switch_languages a#switch_to').toggle(400);
      $('#switch_languages a#current span.downward_arrow').toggle(400);
      $('#switch_languages a#current span.upward_arrow').toggle(400);
    });

    $('nav.navbar ul#main_menu li.first_level').on('mouseover', function() {
      $(this).find('ul.submenu').fadeIn('fast');
      $(this).find('a.first_level_link').trigger('hover');
    });

    $('nav.navbar ul#main_menu li.first_level').on('mouseleave', function() {
      $(this).find('ul.submenu').fadeOut('fast');
    });
  })
} )( jQuery );